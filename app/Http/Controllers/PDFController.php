<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AttendanceComment;
use App\Models\CheckIn;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\TimeTracker;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use DateTime;
use App\Exports\CheckinData;
use App\Exports\AttendanceData;
use PDF;
use Carbon\Carbon;

class PDFController extends Controller
{
    public function generateAttendanceData($user_id, $start_date, $end_date)
    {

        if ($start_date == 0) {
            $start_date = date('Y-m-01');
        }
        if ($end_date == 0) {
            $end_date = date('Y-m-d');
        }
        $employee_info = User::findOrFail($user_id);
        $employee_attendances = collect();
        $current_date = $start_date;
        while (strtotime($current_date) <= strtotime($end_date)) {
            $employee_attendance = [
                'date' => $current_date,
                'check_in' => "Not Checked-In",
                'check_out' => "-",
                'check_out_location' => "-",
                'check_in_location' => "-",
                'remark' => "-",
                'time' => "00:00",
                'status' => "Absent",
                'hr_comment' => "-",
                'comment' => "-"
            ];

            $check_ins = CheckIn::where('user_id', $user_id)
                ->whereDate('start_time', '=', $current_date)
                ->get();

            if ($check_ins->isNotEmpty()) {
                $check_in_end = $check_ins->sortByDesc('start_time')->first();
                $check_in_start = $check_ins->sortBy('start_time')->first();

                if ($check_in_end->end_time != null) {
                    $employee_attendance['check_out'] = date('h:i A', strtotime($check_in_end->end_time));
                    $employee_attendance['check_out_location'] = $check_in_end->end_time_location;
                } else {
                    $employee_attendance['check_out'] = "Yet to Check-out";
                }

                $employee_attendance['check_in'] = date('h:i A', strtotime($check_in_start->start_time));
                $employee_attendance['check_in_location'] = $check_in_start->start_time_location;

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

                if (strlen($remark) > 0) {
                    $employee_attendance['remark'] = "Updated";
                }

                if ($current_date == date('Y-m-d')) {
                    if ($time > config('constants.variable.permitted_work_hours') * 60 * 60) {
                        $employee_attendance['status'] = "Present";
                    }
                } elseif ($time < 5 * 60 * 60) {
                    $employee_attendance['status'] = "Absent";
                } elseif ($time < config('constants.variable.permitted_work_hours') * 60 * 60) {
                    $employee_attendance['status'] = "Half Day";
                } else {
                    $employee_attendance['status'] = "Present";
                }
            }

            $comment = AttendanceComment::where('user_id', $user_id)
                ->where('date', $current_date)
                ->first();

            if ($comment) {
                $employee_attendance['hr_comment'] = $comment->comment;
            }

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
        $leaveExists = Leave::where('user_id', $user_id)
            ->where(function ($query) use ($current_date) {
                $query->where('start_date', '<=', $current_date)
                    ->where('end_date', '>=', $current_date);
            })->first();


        if($leaveExists && $employee_attendance['status'] != 'Present'){
            if($leaveExists->status == "Accepted By HR"){
                $employee_attendance['status'] = "Approved Leave";
            }else{
                $employee_attendance['status'] = "Unapproved Leave";
            }
        }

            $employee_attendances->push($employee_attendance);
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }
        $data = [
            'title' => 'Monthly Attendance',
            'date' => date('m/d/Y'),
            'employee' => $employee_info,
            'attendances' => $employee_attendances,
        ];
        $pdf = PDF::loadView('pdfs.employee-attendance-record', $data)->setPaper('a3', 'landscape');
        return $pdf->download($employee_info->name . ' Attendence Record.pdf');
    }
    public function generateTimeTrackerData($user_id,$start_date,$end_date)
    {
        if ($user_id == 0) {
            $user_id = auth()->user()->id;
        }
        if ($user_id != 'all' || $user_id == null) {
            $count=0;
            $raw_time_trackers = TimeTracker::where('user_id', $user_id)->whereDate('work_date', '>=', $start_date)->whereDate('work_date', '<=', $end_date)->orderBy('work_date', 'desc')->get();
            $time_trackers = array();
            foreach ($raw_time_trackers as $raw_time_tracker) {
                $time_trackers[$raw_time_tracker->work_date][] = $raw_time_tracker;
                $count++;
            }
            $name = User::find($user_id)->name . " " . User::find($user_id)->lastname;
            $users[]=[
                'name'=>$name,
                'id'=>$user_id,
                'count'=>$count,
                'time_trackers' => $time_trackers,
            ];
            $data = [
                'title' => 'Time Tracker Record for ' .$name,
                'download_date' => date('m/d/Y'),
                'users'=> $users,
                'count'=>$count
            ];
            $pdf = PDF::loadView('pdfs.employee-time-tracker', $data)->setPaper('a3', 'landscape');
            return $pdf->download($name . ' Time Tracker Record.pdf');
        }
        elseif ($user_id == 'all'){
            $data = array();
            $employees = User::where('role_id', '!=', 1)->where('employee_status', 1)->get();
            foreach ($employees as $employee) {
                $count=0;
                $raw_time_trackers = TimeTracker::where('user_id', $employee->id)->whereDate('work_date', '>=', $start_date)->whereDate('work_date', '<=', $end_date)->orderBy('work_date', 'desc')->get();
                $time_trackers = array();
                foreach ($raw_time_trackers as $raw_time_tracker) {
                    $time_trackers[$raw_time_tracker->work_date][] = $raw_time_tracker;
                    $count++;
                }
                $users[] = [
                    'name' => $employee->name . " " . $employee->lastname,
                    'time_trackers' => $time_trackers,
                    'count'=>$count,
                ];
                $data = [
                    'title' => 'Time Tracker Record for All employee',
                    'download_date' => date('m/d/Y'),
                    'users' => $users,
                    'name' => $employee->name . " " . $employee->lastname,
                ];
            }
            //return response()->json($data, 200);
            $pdf = PDF::loadView('pdfs.employee-time-tracker', $data)->setPaper('a3', 'landscape');
            return $pdf->download('Time Tracker Record for All Employee.pdf');
        }
    }
    // public function generateTimeTrackerData($user_id, $start_date, $end_date)
    // {
    //     $raw_time_trackers = TimeTracker::where('user_id', $user_id)->whereDate('work_date', '>=', $start_date)->whereDate('work_date', '<=', $end_date)->orderBy('work_date', 'asc')->get();
    //     $time_trackers = array();
    //     foreach ($raw_time_trackers as $raw_time_tracker) {
    //         $time_trackers[$raw_time_tracker->work_date][] = $raw_time_tracker;
    //     }

    //     $employee = User::find($user_id);

    //     $data = [
    //         'title' => 'Time Tracker Record',
    //         'download_date' => date('m/d/Y'),
    //         'employee' => $employee,
    //         'time_trackers' => $time_trackers,
    //     ];

    //     $pdf = PDF::loadView('pdfs.employee-time-tracker', $data);
    //     return $pdf->download($employee->name . ' Time Tracker Record.pdf');
    // }

      public function generateAttendanceReport($user_id, $month, $year){

        // Get the first date of the month
        $start_date = date('Y-m-01', strtotime("$year-$month-01"));

        // Get the last date of the month
        $end_date = date('Y-m-t', strtotime("$year-$month-01"));

        $attendance_data = collect();
        if ($user_id!=0) {
            $user = User::find($user_id);
            $details=[
                'id'=>$user->id,
                'name'=>$user->name,
                'lastname'=>$user->lastname ?? " ",
                'email'=>$user->email,
            ];
            $data = [
                'detail' => $details,
                'record' => $user->transformEmployee($user->id, $start_date, $end_date)
            ];
            $attendance_data->push($data);
        } else {
            $employees = User::where('role_id', '!=', 1)->where('employee_status', 1)->get();
            foreach ($employees as $employee) {
                $details = [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'lastname' => $employee->lastname ?? null,
                    'email' => $employee->email,
                ];
                $data = [
                    'detail' => $details,
                    'record' => $employee->transformEmployee($employee->id, $start_date, $end_date)
                ];
                $attendance_data->push($data);
            }
        }

        $data=[
            'title' => 'Monthly Attendance Report',
            'download_date' => date('m/d/Y'),
            'month' => Carbon::create()->month($month)->format('F'),
            'days' => Carbon::create()->month($month)->daysInMonth,
            'year' => $year,
             'date'=>date('Y-m-d'),
            'attendance_data' => $attendance_data->toArray(),
        ];


        //dd($data['attendance_data'][0]);
        //excel
       return Excel::download(new AttendanceData($data), Carbon::create()->month($month)->format('F') . "_" . $year . '_Attendance_Report.xlsx');
      }

    public function generateCheckinsReport($user_id,$month,$year){

        // Get the first date of the month
        $start_date = date('Y-m-01', strtotime("$year-$month-01"));

        // Get the last date of the month
        $end_date = date('Y-m-t', strtotime("$year-$month-01"));

        $checkins_data = collect();
        if($user_id!=0){
            $user = User::find($user_id);
            $details = [
                'id' => $user->id,
                'name' => $user->name,
                'lastname' => $user->lastname ?? " ",
                'email' => $user->email,
            ];
            $data = [
                'detail' => $details,
                'record' => $user->transformCheckins($user->id, $start_date, $end_date)
            ];
            $checkins_data->push($data);
        }
        else{
            $employees = User::where('role_id', '!=', 1)->where('employee_status', 1)->get();
            foreach ($employees as $employee) {
                $details = [
                    'id' => $employee->id,
                    'name' => $employee->name ." " . $employee->lastname ?? ' ',
                    'email' => $employee->email,
                ];
                $data = [
                    'detail' => $details,
                    'record' => $employee->transformCheckins($employee->id, $start_date, $end_date)
                ];
                $checkins_data->push($data);
            }
        }
        $data = [
            'title' => 'Monthly Checkin-Checkout Report',
            'download_date' => date('m/d/Y'),
            'month' => Carbon::create()->month($month)->format('F'),
            'year' => $year,
            'days' => Carbon::create()->month($month)->daysInMonth,
            'checkins_data' => $checkins_data->toArray(),
        ];
        //dd($checkins_data);
        //excel
       return Excel::download(new CheckinData($data), Carbon::create()->month($month)->format('F') . "_" . $year . '_Checkin_Report.xlsx');
        //pdf download
       // $pdf = PDF::loadView('pdfs.employee-monthly-checkins-report',$data)->setPaper('a3', 'landscape');
        //return $pdf->download(Carbon::create()->month($month)->format('F') . "/" . $year . '_Attendance_Report.pdf');
    }
}
