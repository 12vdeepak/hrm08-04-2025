<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\AttendanceComment;
use App\Models\CheckIn;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display the attendance details of employees for a specific date.
     *
     * @param  string|null  $date
     * @return \Illuminate\Contracts\View\View
     */
    public function employee_attendance($date = null)
    {
        return view('HR.attendance');
    }

    public function attendance_report(){
        return view('HR.employee.report');
    }

    public function add_comment(Request $request)
    {
        $comment = AttendanceComment::where('user_id', $request->id)->where('date', $request->date)->first();
        if ($comment) {
            $comment->comment = $request->comment;
            $comment->save();
        } else {
            $comment = new AttendanceComment();
            $comment->user_id = $request->id;
            $comment->date = $request->date;
            $comment->comment = $request->comment;
            $comment->save();
        }
        return redirect()->route('employee_attendence', ['date' => $request->date]);
    }

    public function update_comment(Request $request)
    {
        $comment = AttendanceComment::where('user_id', $request->id)->where('date', $request->date)->first();
        if ($comment) {
            $comment->comment = $request->comment;
            $comment->save();
        } else {
            $comment = new AttendanceComment();
            $comment->user_id = $request->id;
            $comment->date = $request->date;
            $comment->comment = $request->comment;
            $comment->save();
        }
        return redirect()->route('employee_detail', ['id' => $request->id, 'start_date' => $request->start_date, 'end_date' => $request->end_date]);
    }
}
