<?php


namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\AttendanceComment;
use App\Models\CheckIn;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EmployeeDetailController extends Controller
{
    public function employee_detail($id, $start_date, $end_date)
    {
        if ($start_date == 0) {
            $start_date = date('Y-m-01');
        }
        if ($end_date == 0) {
            $end_date = date('Y-m-d');
        }

        $employee_info = User::findOrFail($id);
        $employee_attendances = collect();
        $current_date = $start_date;

        while (strtotime($current_date) <= strtotime($end_date)) {
            $employee_attendance = [
                'date' => $current_date,
                'check_in' => "Not Checked In",
                'check_out' => "-",
                'check_out_location' => "-",
                'check_in_location' => "-",
                'remark' => "-",
                'time' => "00:00",
                'status' => "Absent",
                'hr_comment' => "-",
                'comment' => "-"
            ];

            $check_ins = CheckIn::where('user_id', $id)
                ->whereDate('start_time', '=', $current_date)
                ->get();

            if ($check_ins->isNotEmpty()) {
                $check_in_end = $check_ins->sortByDesc('start_time')->first();
                $check_in_start = $check_ins->sortBy('start_time')->first();

                // Set check-in and check-out details
                if ($check_in_end->end_time != null) {
                    $employee_attendance['check_out'] = date('h:i A', strtotime($check_in_end->end_time));
                    $employee_attendance['check_out_location'] = $check_in_end->end_time_location;
                } else {
                    $employee_attendance['check_out'] = "Yet to Check-out";
                }
                $employee_attendance['check_in'] = date('h:i A', strtotime($check_in_start->start_time));
                $employee_attendance['check_in_location'] = $check_in_start->start_time_location;

                // Calculate total time and set remark
                $time = 0;
                $remark = "";
                foreach ($check_ins as $check_in) {
                    $remark = $check_in->remark;
                    if ($check_in->end_time != null) {
                        $time += strtotime($check_in->end_time) - strtotime($check_in->start_time);
                    } else {
                        $time += strtotime(date('Y-m-d H:i:s')) - strtotime($check_in->start_time);
                    }
                }
                $employee_attendance['time'] = gmdate('H:i', $time);
                $employee_attendance['remark'] = (strlen($remark) > 0) ? "Updated" : "-";

                // Set attendance status
                
                if ($current_date == date('Y-m-d')) {
                    $employee_attendance['status'] = ($time > config('constants.variable.permitted_work_hours') * 60 * 60) ? "Present" : "-";
                } elseif ($time < 5 * 60 * 60) {
                    $employee_attendance['status'] = "Absent";
                } elseif ($time < config('constants.variable.permitted_work_hours') * 60 * 60) {
                    $employee_attendance['status'] = "Half Day";
                } else {
                    $employee_attendance['status'] = "Present";
                }
            }

            // Get HR comment for the date
            $comment = AttendanceComment::where('user_id', $id)
                ->where('date', $current_date)
                ->first();
            if ($comment) {
                $employee_attendance['hr_comment'] = $comment->comment;
            }

            // Check for holiday and weekend
            if (Holiday::where('start_date', '<=', $current_date)
                ->where('end_date', '>=', $current_date)
                ->exists()
            ) {
                $employee_attendance['comment'] = "Holiday";
                $employee_attendance['status'] = "Holiday";
            } elseif (in_array(date('l', strtotime($current_date)), ['Sunday', 'Saturday'])) {
                $employee_attendance['comment'] = "Weekend";
                $employee_attendance['status'] = "Weekend";
            } else {
                // Check-in related comments
                if ($employee_attendance['check_in'] != "Yet to Check-in") {
                    if ($employee_attendance['check_in'] > DateTime::createFromFormat('h:i A', '11:00 AM')) {
                        if ($employee_info->working_hours == 0) {
                            if ($employee_attendance['check_out'] != "-" && $employee_attendance['check_out'] != "Yet to Check-out") {
                                if ($employee_attendance['check_in'] < DateTime::createFromFormat('h:i A', '7:00 PM')) {
                                    $employee_attendance['comment'] = "Late Check-in and Early Check-out";
                                } else {
                                    $employee_attendance['comment'] = "Late Check-in";
                                }
                            } else {
                                $employee_attendance['comment'] = "Late Check-in";
                            }
                        } else {
                            $employee_attendance['comment'] = "Flexible Working Hours";
                        }
                    } else {
                        if ($employee_attendance['check_out'] != "-" && $employee_attendance['check_out'] != "Yet to Check-out") {
                            if ($employee_attendance['check_in'] < DateTime::createFromFormat('h:i A', '7:00 PM')) {
                                if ($employee_info->working_hours == 0) {
                                    $employee_attendance['comment'] = "Early Check-out";
                                } else {
                                    $employee_attendance['comment'] = "Flexible Working Hours";
                                }
                            } else {
                                $employee_attendance['comment'] = "-";
                            }
                        } else {
                            $employee_attendance['comment'] = "-";
                        }
                    }
                } else {
                    $employee_attendance['comment'] = "-";
                }
            }

            //check if applied for leave at this date and approved
            $leaveExists = Leave::where('user_id', $id)
                ->where(function ($query) use ($current_date) {
                    $query->where('start_date', '<=', $current_date)
                        ->where('end_date', '>=', $current_date);
                })->first();


            if ($leaveExists && $employee_attendance['status'] != 'Present') {
                if ($leaveExists->status == "Accepted By HR") {
                    $employee_attendance['status'] = "Approved Leave";
                } else {
                    $employee_attendance['status'] = "Unapproved Leave";
                }
            }

            $employee_attendances->push($employee_attendance);
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }

        return view('HR.employee.view', compact('start_date', 'end_date', 'employee_info', 'employee_attendances'));
    }




    public function update_log_time(Request $request)
    {
      $check_ins = CheckIn::where('user_id', $request->id)->whereDate('start_time', '=', $request->date)->get();
        foreach ($check_ins as $check_in) {
            $check_in->delete();
        }
        
        $updated_start_time=new DateTime("$request->date  $request->start_time");
        $updated_start_time->format('Y-m-d H:i:s');
        
        $updated_end_time=new DateTime("$request->date   $request->end_time");
        $updated_end_time->format('Y-m-d H:i:s');

        $new_check_in = new CheckIn;
        $new_check_in->user_id = $request->id;
        $new_check_in->start_time =$updated_start_time; 
        if($request->end_time !=NULL)
        $new_check_in->end_time = $updated_end_time;
        $new_check_in->remark = "Updated";
        $new_check_in->save();
        return redirect()->route('employee_detail', ['id' => $request->id, 'start_date' => $request->start_date, 'end_date' => $request->end_date]);
     }
}
