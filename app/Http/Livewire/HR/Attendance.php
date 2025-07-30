<?php

namespace App\Http\Livewire\HR;

use Livewire\Component;
use App\Models\AttendanceComment;
use App\Models\CheckIn;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
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
        // These two methods do the same thing, they clear the error bag.
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
            $employee = $this->transformEmployee($employee);
            return $employee;
        });
        // $employees = $query->paginate(1);
        // $employees->getCollection()->transform(function ($employee) {
        //     $employee = $this->transformEmployee($employee);
        //     return $employee;
        // });

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
        // dd($employees);
        $hasPagination = $employees instanceof LengthAwarePaginator && $employees->hasPages();
        // dd($hasPagination);
        return view('livewire.h-r.attendance', compact('employees', 'hasPagination'));
    }

    private function transformEmployee($employee)
    {
        $date = $this->date;

        $comment = AttendanceComment::where('user_id', $employee->id)->where('date', $date)->first();
        $employee->comment = $comment ? $comment->comment : '-';

        $check_in_end = CheckIn::where('user_id', $employee->id)->whereDate('start_time', '=', $date)->orderBy('start_time', 'desc')->first();

        if ($check_in_end) {
            if ($check_in_end->end_time !== null) {
                $employee->check_out_time = date('h:i A', strtotime($check_in_end->end_time));
                $employee->check_out_location = $check_in_end->end_time_location;
            } else {
                $employee->check_out_time = "Yet to Check-out";
                $employee->check_out_location = "-";
            }

            $check_in_start = CheckIn::where('user_id', $employee->id)->whereDate('start_time', '=', $date)->orderBy('start_time', 'asc')->first();
            $employee->check_in_time = date('h:i A', strtotime($check_in_start->start_time));
            $employee->check_in_location = $check_in_start->start_time_location;
        } else {
            $employee->check_in_time = "Yet to Check-in";
            $employee->check_out_time = "-";
            $employee->check_out_location = "-";
            $employee->check_in_location = "-";
        }

        $check_ins = CheckIn::where('user_id', $employee->id)->whereDate('start_time', '=', $date)->get();
        $total_time = 0;

        foreach ($check_ins as $check_in) {
            if ($check_in->end_time !== null) {
                $total_time += strtotime($check_in->end_time) - strtotime($check_in->start_time);
            } else {
                if ($date === date('Y-m-d')) {
                    $total_time += strtotime(date('Y-m-d H:i:s')) - strtotime($check_in->start_time);
                } else {
                    $total_time = 0;
                    break;
                }
            }
        }
        $employee->time = gmdate('H:i', $total_time);
        if ($date === date('Y-m-d')) {
            if ($total_time >= config('constants.variable.permitted_work_hours') * 60 * 60) {
                $employee->attendance = 'Present';
            } else {
                $employee->attendance = '-';
            }
        } elseif ($total_time < 3 * 60 * 60) {
            $employee->attendance = 'Absent';
        } elseif ($total_time < config('constants.variable.permitted_work_hours') * 60 * 60) {
            $employee->attendance = 'Half Day';
        }
        else {
            $employee->attendance = 'Present';
        }

        // Check for holiday and weekend
        if (Holiday::where('start_date', '<=', $date)->where('end_date', '>=', $date)->exists()) {
            $employee->attendance = "Holiday"; //Holiday
        } elseif (in_array(date('l', strtotime($date)), ['Sunday', 'Saturday'])) {
            $employee->attendance = "Weekend"; //Weekend
        }

        //check if applied for leave at this date and approved
        $leaveExists = Leave::where('user_id', $employee->id)
            ->where(function ($query) use ($date) {
                $query->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date);
            })->first();


        if($leaveExists && $employee->attendance != 'Present'){
            if($leaveExists->status == "Accepted By HR"){
                $employee->attendance = "Approved Leave";
            }else{
                $employee->attendance = "Unapproved Leave";
            }
        }

        if ($employee->check_in_time == "Yet to Check-in" && $employee->check_out_time == "-") {
            $employee->log_status = 2; // Yet to Check-in
        } elseif ($employee->check_in_time != "Yet to Check-in" && $employee->check_out_time == "Yet to Check-out") {
            $employee->log_status = 1; // Checked-in
        } elseif ($employee->check_in_time != "Yet to Check-in" && ($employee->check_out_time != "Yet to Check-out" || $employee->check_out_time != "-")) {
            $employee->log_status = 3; // Checked-out
        } elseif ($employee->check_in_time != "Yet to Check-in" && $employee->check_out_time == "-") {
            $employee->log_status = 4; // Yet to Check-out
        }

        // Add is_late property
        $employee->is_late = false;
        if ($employee->check_in_time && $employee->check_in_time != "Yet to Check-in") {
            if (\Carbon\Carbon::parse($employee->check_in_time)->format('H:i:s') > '11:00:00') {
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

        // Save the comment to the database
        AttendanceComment::create([
            'comment' => $this->comment,
            'date' => $this->date,
            'user_id' => $this->selectedEmployeeId,
            // Add any other necessary fields for the comment record
        ]);

        session()->flash('success', 'Comment added successfully');

        $this->resetInputs();
        // Close the modal after saving the comment
        $this->closeModal();
    }
    public function editCommentInit($id)
    {
        $this->selectedEmployeeId = $id;
        $data = AttendanceComment::where('date', $this->date)->where('user_id', $id)->first();
        $this->comment = $data->comment;
        $this->dispatchBrowserEvent('show-edit-comment-modal');
    }

    public function editComment()
    {

        $this->validate();

        $data = AttendanceComment::where('date', $this->date)->where('user_id', $this->selectedEmployeeId)->first();
        // Update the comment to the database
        $data->comment = $this->comment;
        $data->save();

        session()->flash('success', 'Comment updated successfully');

        $this->resetInputs();
        // Close the modal after saving the comment
        $this->closeModal();
    }

    public function resetInputs()
    {
        $this->comment = '';
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
