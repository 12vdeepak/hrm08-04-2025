<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AttendanceComment;
use App\Models\CheckIn;
use App\Models\Holiday;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;

class EmployeeAttendanceController extends Controller
{
    public function attendance($start_date, $end_date)
    {
        if ($start_date == 0) {
            $today = date('Y-m-d');
            $start_date = date('Y-m-01', strtotime($today));
        }
        if ($end_date == 0) {
            $end_date = date('Y-m-d');
        }
        $id = auth()->user()->id;
        $employee_info = User::where('id', $id)->first();
        $employee_attendances = array();
        $current_date = $start_date;
        while (strtotime($current_date) <= strtotime($end_date)) {
            $employee_attendance = array();
            $employee_attendance['date'] = $current_date;
            $check_in_end = CheckIn::where('user_id', $id)->whereDate('start_time', '=', $current_date)->orderBy('start_time', 'desc')->first();
            if ($check_in_end) {
                if ($check_in_end->end_time != null) {
                    $employee_attendance['check_out'] = date('h:i A', strtotime($check_in_end->end_time));
                } else {
                    $employee_attendance['check_out'] = "Not Checked Out";
                }
                $check_in_end = CheckIn::where('user_id', $id)->whereDate('start_time', '=', $current_date)->orderBy('start_time', 'asc')->first();
                $employee_attendance['check_in'] = date('h:i A', strtotime($check_in_end->start_time));
            } else {
                $employee_attendance['check_in'] = "Not Checked In";
                $employee_attendance['check_out'] = "-";
            }
            $check_ins = CheckIn::where('user_id', $id)->whereDate('start_time', '=', $current_date)->get();
            $time = 0;
            foreach ($check_ins as $check_in) {
                if ($check_in->end_time != null) {
                    $time = $time + strtotime($check_in->end_time) - strtotime($check_in->start_time);
                } else {
                    if ($current_date == date('Y-m-d')) {
                        $time = $time + strtotime(date('Y-m-d H:i:s')) - strtotime($check_in->start_time);
                    } else {
                        $time = 0;
                        break;
                    }
                }
            }
            $employee_attendance['time'] = gmdate('H:i', $time);
            if ($current_date == date('Y-m-d')) {
                if ($time >= config('constants.variable.permitted_work_hours') * 60 * 60) {
                    $employee_attendance['status'] = "Present";
                } else {
                    $employee_attendance['status'] = "-";
                }
            } elseif ($time < 5 * 60 * 60) {
                $employee_attendance['status'] = "Absent";
            } elseif ($time < config('constants.variable.permitted_work_hours') * 60 * 60) {
                $employee_attendance['status'] = "Half Day";
            } else {
                $employee_attendance['status'] = "Present";
            }
            $comment = AttendanceComment::where('user_id', $id)->where('date', $current_date)->first();
            if ($comment) {
                $employee_attendance['hr_comment'] = $comment->comment;
            } else {
                $employee_attendance['hr_comment'] = "-";
            }
            if (Holiday::where('start_date', '<=', $current_date)->where('end_date', '>=', $current_date)->first()) {
                $employee_attendance['comment'] = "Holiday";
                $employee_attendance['status'] = "Holiday";
            } elseif (date('l', strtotime($current_date)) == "Sunday" || date('l', strtotime($current_date)) == "Saturday") {
                $employee_attendance['comment'] = "Weekend";
                $employee_attendance['status'] = "Weekend";
            } else {
                if ($employee_attendance['check_in'] != "Not Checked In") {
                    if ($employee_attendance['check_in'] > DateTime::createFromFormat('h:i A', '11:00 AM')) {
                        if ($employee_info->working_hours == 0) {
                            if ($employee_attendance['check_out'] != "-" && $employee_attendance['check_out'] != "Not Checked Out") {
                                if ($employee_attendance['check_in'] < DateTime::createFromFormat('h:i A', '7:00 PM')) {
                                    $employee_attendance['comment'] = "Late Check Inn and Early Check Out";
                                } else {
                                    $employee_attendance['comment'] = "Late Check Inn";
                                }
                            } else {
                                $employee_attendance['comment'] = "Late Check Inn";
                            }
                        } else {
                            $employee_attendance['comment'] = "Flexible Working Hours";
                        }
                    } else {
                        if ($employee_attendance['check_out'] != "-" && $employee_attendance['check_out'] != "Not Checked Out") {
                            if ($employee_attendance['check_in'] < DateTime::createFromFormat('h:i A', '7:00 PM')) {
                                if ($employee_info->working_hours == 0) {
                                    $employee_attendance['comment'] = "Early Check Out";
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
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
            $employee_attendances[] = $employee_attendance;
        }
        return view('User.attendance', compact('start_date', 'end_date', 'employee_attendances'));
    }
}
