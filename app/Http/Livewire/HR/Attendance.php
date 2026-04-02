<?php

namespace App\Http\Livewire\HR;

use Livewire\Component;
use App\Models\AttendanceComment;
use App\Models\CheckIn;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\User;
use App\Helpers\ShiftHelper;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class Attendance extends Component
{
    use WithPagination;

    public string $search = '';
    protected $paginationTheme = 'bootstrap';

    public $date, $comment, $selectedEmployeeId, $status = 0;
    public bool $isModalOpen = false;
    protected $rules = [
        'comment' => 'required|string',
    ];

    protected $listeners = [
        'forceClosedModal',
    ];

    public function closeModal()
    {
        $this->resetInputs();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function forceClosedModal()
    {
        $this->resetInputs();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
        if (!$this->date) {
            $this->date = date('Y-m-d');
        }
    }

    public function render()
    {
        $query = User::query()->where('role_id', '!=', 1)->where('employee_status', 1);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('lastname', 'like', "%{$this->search}%");
            });
        }

        $employees = $query->get();
        $employees->transform(function ($employee) {
            return $this->transformEmployee($employee);
        });

        // Filter employees based on status
        if ($this->status != 0) {
            $employees = $employees->filter(function ($employee) {
                if ($this->status == 5) {
                    return $employee->is_late == true;
                } else {
                    return $employee->log_status == $this->status;
                }
            });
        }

        $hasPagination = $employees instanceof LengthAwarePaginator && $employees->hasPages();
        return view('livewire.h-r.attendance', compact('employees', 'hasPagination'));
    }

    private function transformEmployee($employee)
    {
        $date       = $this->date;
        $shift_type = $employee->shift_type ?? 'day';

        $comment             = AttendanceComment::where('user_id', $employee->id)->where('date', $date)->first();
        $employee->comment   = $comment ? $comment->comment : '-';

        // Query by shift_date column
        $check_in_end = CheckIn::where('user_id', $employee->id)
            ->where('shift_date', $date)
            ->orderBy('start_time', 'desc')
            ->first();

        if ($check_in_end) {
            if ($check_in_end->end_time !== null) {
                $employee->check_out_time     = date('h:i A', strtotime($check_in_end->end_time));
                $employee->check_out_location = $check_in_end->end_time_location;
            } else {
                $employee->check_out_time     = "Yet to Check-out";
                $employee->check_out_location = "-";
            }

            $check_in_start             = CheckIn::where('user_id', $employee->id)
                ->where('shift_date', $date)
                ->orderBy('start_time', 'asc')
                ->first();
            $employee->check_in_time     = date('h:i A', strtotime($check_in_start->start_time));
            $employee->check_in_location = $check_in_start->start_time_location;
        } else {
            $employee->check_in_time     = "Yet to Check-in";
            $employee->check_out_time    = "-";
            $employee->check_out_location = "-";
            $employee->check_in_location = "-";
        }

        // Sum completed durations for this shift date
        $check_ins  = CheckIn::where('user_id', $employee->id)->where('shift_date', $date)->get();
        $total_time = 0;

        foreach ($check_ins as $check_in) {
            if ($check_in->end_time !== null) {
                $total_time += strtotime($check_in->end_time) - strtotime($check_in->start_time);
            } else {
                $today_shift_date = ShiftHelper::resolveShiftDate($shift_type);
                if ($date === $today_shift_date) {
                    $total_time += strtotime(date('Y-m-d H:i:s')) - strtotime($check_in->start_time);
                } else {
                    $total_time = 0;
                    break;
                }
            }
        }

        $employee->time = gmdate('H:i', $total_time);

        $today_shift_date = ShiftHelper::resolveShiftDate($shift_type);
        if ($date === $today_shift_date) {
            $employee->attendance = $total_time >= config('constants.variable.permitted_work_hours') * 60 * 60
                ? 'Present' : '-';
        } elseif ($total_time < 3 * 60 * 60) {
            $employee->attendance = 'Absent';
        } elseif ($total_time < config('constants.variable.permitted_work_hours') * 60 * 60) {
            $employee->attendance = 'Half Day';
        } else {
            $employee->attendance = 'Present';
        }

        // Holiday & weekend override
        if (Holiday::where('start_date', '<=', $date)->where('end_date', '>=', $date)->exists()) {
            $employee->attendance = "Holiday";
        } elseif (in_array(date('l', strtotime($date)), ['Sunday', 'Saturday'])) {
            $employee->attendance = "Weekend";
        }

        // Leave check
        $leaveExists = Leave::where('user_id', $employee->id)
            ->where(function ($query) use ($date) {
                $query->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date);
            })->first();

        if ($leaveExists && $employee->attendance != 'Present') {
            $employee->attendance = $leaveExists->status == "Accepted By HR"
                ? "Approved Leave" : "Unapproved Leave";
        }

        // log_status
        if ($employee->check_in_time == "Yet to Check-in" && $employee->check_out_time == "-") {
            $employee->log_status = 2;
        } elseif ($employee->check_in_time != "Yet to Check-in" && $employee->check_out_time == "Yet to Check-out") {
            $employee->log_status = 1;
        } elseif ($employee->check_in_time != "Yet to Check-in" && ($employee->check_out_time != "Yet to Check-out" || $employee->check_out_time != "-")) {
            $employee->log_status = 3;
        } elseif ($employee->check_in_time != "Yet to Check-in" && $employee->check_out_time == "-") {
            $employee->log_status = 4;
        }

        // is_late — use shift-aware late cutoff
        $late_cutoff      = ShiftHelper::lateCutoff($shift_type); // '11:00:00' or '20:00:00'
        $employee->is_late = false;
        if ($employee->check_in_time && $employee->check_in_time != "Yet to Check-in") {
            if (Carbon::parse($employee->check_in_time)->format('H:i:s') > $late_cutoff) {
                $employee->is_late = true;
            }
        }

        return $employee;
    }

    public function addCommentInit($id)
    {
        $this->selectedEmployeeId = $id;
        $this->dispatchBrowserEvent('show-add-comment-modal');
    }

    public function addComment()
    {
        $this->validate();

        AttendanceComment::create([
            'comment' => $this->comment,
            'date'    => $this->date,
            'user_id' => $this->selectedEmployeeId,
        ]);

        session()->flash('success', 'Comment added successfully');
        $this->resetInputs();
        $this->closeModal();
    }

    public function editCommentInit($id)
    {
        $this->selectedEmployeeId = $id;
        $data                     = AttendanceComment::where('date', $this->date)->where('user_id', $id)->first();
        $this->comment            = $data->comment;
        $this->dispatchBrowserEvent('show-edit-comment-modal');
    }

    public function editComment()
    {
        $this->validate();

        $data          = AttendanceComment::where('date', $this->date)->where('user_id', $this->selectedEmployeeId)->first();
        $data->comment = $this->comment;
        $data->save();

        session()->flash('success', 'Comment updated successfully');
        $this->resetInputs();
        $this->closeModal();
    }

    public function resetInputs()
    {
        $this->comment            = '';
        $this->selectedEmployeeId = null;
    }

    public function updated($property)
    {
        if ($property == 'search' || $property == 'date' || $property == 'status') {
            $this->resetPage();
        }

        if ($property == 'comment') {
            $this->validateOnly($property);
        }
    }
}
