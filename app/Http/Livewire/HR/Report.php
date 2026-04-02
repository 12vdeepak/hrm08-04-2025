<?php

namespace App\Http\Livewire\HR;

use App\Models\AttendanceComment;
use App\Models\CheckIn;
use App\Models\Holiday;
use App\Models\User;
use App\Models\Leave;
use App\Helpers\ShiftHelper;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Report extends Component
{
    use WithPagination;

    public $month, $year, $days, $employees, $selectedEmployeeId, $presentDay,
        $selectedEmployee, $search, $subSelectedEmployeeId, $selectedDate,
        $selectedClockInTime, $selectedClockOutTime, $selectedClockInIP, $selectedClockOutIP,
        $selectedClockInLocation, $selectedClockOutLocation, $selectedTotalTime, $progressPercent;
    public bool $isModalOpen = false;

    protected $listeners = [
        'forceClosedModal',
    ];

    public function closeModal()
    {
        $this->dispatchBrowserEvent('close-modal');
    }

    public function forceClosedModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function presentModalInit($subSelectedEmployeeId, $selectedDateModal)
    {
        $make_date                     = $this->year . '-' . $this->month . '-' . $selectedDateModal;
        $this->subSelectedEmployeeId   = $subSelectedEmployeeId;
        $this->selectedDate            = $make_date;
        $this->setSelectedEmployeeDateData();
        $this->dispatchBrowserEvent('show-present-modal');
    }

    public function setSelectedEmployeeDateData()
    {
        // Query by shift_date
        $check_ins = CheckIn::where('user_id', $this->subSelectedEmployeeId)
            ->where('shift_date', $this->selectedDate)
            ->get();

        if ($check_ins->isNotEmpty()) {
            $check_in_end   = $check_ins->sortByDesc('start_time')->first();
            $check_in_start = $check_ins->sortBy('start_time')->first();

            if ($check_in_end->end_time != null) {
                $this->selectedClockOutTime     = date('h:i A', strtotime($check_in_end->end_time));
                $this->selectedClockOutIP       = $check_in_end->out_ip_address;
                $this->selectedClockOutLocation = $check_in_end->end_time_location;
            } else {
                $this->selectedClockOutTime = "Not Checked-out";
            }
            $this->selectedClockInTime     = date('h:i A', strtotime($check_in_start->start_time));
            $this->selectedClockInLocation = $check_in_start->start_time_location;
            $this->selectedClockInIP       = $check_in_start->in_ip_address;

            $time = 0;
            foreach ($check_ins as $check_in) {
                if ($check_in->end_time != null) {
                    $time += strtotime($check_in->end_time) - strtotime($check_in->start_time);
                } else {
                    $time += strtotime(date('Y-m-d H:i:s')) - strtotime($check_in->start_time);
                }
            }

            $workHours = round($time / 3600, 2);
            $this->progressPercent    = $workHours >= 9 ? 100 : round(($workHours / 9) * 100, 2);
            $this->selectedTotalTime  = gmdate('H:i', $time);
        }
    }

    public function render()
    {
        $this->updateDays();

        $attendance_data = collect();
        if ($this->selectedEmployee) {
            $data = [
                'detail' => $this->selectedEmployee,
                'record' => $this->transformEmployee($this->selectedEmployee->id),
            ];
            $attendance_data->push($data);
        } else {
            foreach ($this->employees as $employee) {
                $data = [
                    'detail' => $employee,
                    'record' => $this->transformEmployee($employee->id),
                ];
                $attendance_data->push($data);
            }
        }
        return view('livewire.h-r.report', compact('attendance_data'));
    }

    public function transformEmployee($user_id)
    {
        $start_date    = date('Y-m-01', strtotime("{$this->year}-{$this->month}-01"));
        $end_date      = date('Y-m-t', strtotime("{$this->year}-{$this->month}-01"));
        $employee_info = User::findOrFail($user_id);
        $shift_type    = $employee_info->shift_type ?? 'day';

        $employee_attendances = collect();
        $days_present = 0;
        $days_absent  = 0;
        $holiday      = 0;
        $weekend      = 0;

        while (strtotime($start_date) <= strtotime($end_date)) {
            $employee_attendance = [
                'date'   => $start_date,
                'status' => 0,
            ];

            // Query by shift_date
            $check_ins = CheckIn::where('user_id', $user_id)
                ->where('shift_date', $start_date)
                ->get();

            if ($check_ins->isNotEmpty()) {
                $time = 0;
                foreach ($check_ins as $check_in) {
                    if ($check_in->end_time != null) {
                        $time += strtotime($check_in->end_time) - strtotime($check_in->start_time);
                    } else {
                        $time += strtotime(date('Y-m-d H:i:s')) - strtotime($check_in->start_time);
                    }
                }

                $today_shift_date = ShiftHelper::resolveShiftDate($shift_type);
                if ($start_date == $today_shift_date) {
                    $employee_attendance['status'] = ($time > config('constants.variable.permitted_work_hours') * 60 * 60) ? 1 : 2;
                } elseif ($time < 4.5 * 60 * 60) {
                    $employee_attendance['status'] = 0; // Absent
                    $days_absent += 1;
                } elseif ($time < (config('constants.variable.permitted_work_hours') * 60 * 60) && $time > (round(config('constants.variable.permitted_work_hours') / 2, 2) * 60 * 60)) {
                    $employee_attendance['status'] = 0.5; // Half Day
                    $days_present += 0.5;
                    $days_absent  += 0.5;
                } else {
                    $employee_attendance['status'] = 1; // Present
                    $days_present += 1;
                }
            } else {
                if ($start_date <= date('Y-m-d')) {
                    $employee_attendance['status'] = 0; // Absent
                    $days_absent += 1;
                }
            }

            // Holiday & weekend override
            if (Holiday::where('start_date', '<=', $start_date)->where('end_date', '>=', $start_date)->exists()) {
                $employee_attendance['status'] = 4; // Holiday
                $holiday += 1;
            } elseif (in_array(date('l', strtotime($start_date)), ['Sunday', 'Saturday'])) {
                $employee_attendance['status'] = 3; // Weekend
                $weekend += 1;
            }

            $employee_attendances->push($employee_attendance);
            $start_date = date('Y-m-d', strtotime($start_date . ' +1 day'));
        }

        return [
            'attendance'   => $employee_attendances,
            'days_present' => $days_present,
            'days_absent'  => $days_absent - $weekend - $holiday,
        ];
    }

    public function mount()
    {
        if (!$this->month || !$this->year) {
            $today        = date('Y-m-d');
            $this->month  = (int) date('m', strtotime($today));
            $this->year   = (int) date('Y', strtotime($today));
        }

        $this->updateDays();
        $this->employees = User::where('role_id', '!=', 1)->where('employee_status', '!=', 'Left')->get();
    }

    public function updated($property)
    {
        if ($property == 'month' || $property == 'year') {
            $this->updateDays();
            $this->resetPage();
        }
        if ($property == 'selectedEmployeeId') {
            $this->selectedEmployee = User::find($this->selectedEmployeeId);
            $this->resetPage();
        }
    }

    private function updateDays()
    {
        if ($this->month && $this->year) {
            $last_date  = date('Y-m-t', strtotime("{$this->year}-{$this->month}-01"));
            $this->days = date('d', strtotime($last_date));
        }
    }
}
