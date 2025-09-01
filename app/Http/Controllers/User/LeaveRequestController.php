<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Events\Leave as EventsLeave;
use App\Mail\leaverequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaveNotification;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendLeaveEmail;
use Exception;

class LeaveRequestController extends Controller
{

   public function add_leave_request(Request $request){
    $leave_request = new Leave();
    $leave_request->user_id = auth()->user()->id;
    $leave_request->subject = $request->subject;
    $leave_request->type = $request->type;
    $leave_request->description = $request->description;
    $leave_request->start_date = $request->start_date;
    $leave_request->end_date = $request->end_date;
    $leave_request->status = "Requested";
    $leave_request->reporting_manager_email = $request->reporting_manager_email;
    $leave_request->save();

    // Generate approval token
    $approval_token = $leave_request->generateApprovalToken();

    // HR notifications
    $users = User::where('role_id', 2)->get();
    foreach ($users as $user) {
        Notification::send($user, new LeaveNotification($leave_request));
    }

    // Mail send to reporting manager and hr
    $reporting_manager = $request->reporting_manager_email;
    $hr_emails = User::where('employee_type_id', 2)->where('employee_status', 1)->pluck('email')->toArray();

    // Add your 2 additional CC emails here
    $additional_cc_emails = [
        'mansi@quantumitinnovation.com ',    // Replace with actual email 1
        'hr@quantumitinnovation.com'     // Replace with actual email 2
    ];

    // Merge HR emails with additional CC emails
    $all_cc_emails = array_merge($hr_emails, $additional_cc_emails);

    // Calculate duration
    $start_date = \Carbon\Carbon::parse($request->start_date);
    $end_date = \Carbon\Carbon::parse($request->end_date);
    $duration = $start_date->diffInDays($end_date) + 1;

    $details = [
        'body' => "New Leave Request from " . auth()->user()->name . " " . auth()->user()->last_name ?? ' ',
        'name' => auth()->user()->name . " " . auth()->user()->last_name ?? ' ',
        'employee_email' => auth()->user()->email,
        'employee_id' => auth()->user()->employee_id ?? 'EMP' . auth()->user()->id,
        'designation' => auth()->user()->designation ?? 'Employee',
        'contact' => auth()->user()->phone ?? '+1 (555) 123-4567',
        'subject' => $request->subject,
        'type' => $request->type,
        'description' => $request->description,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'duration' => $duration . ' day(s)',
        'status' => "Requested",
        // Add approval URLs
        'approve_url' => route('leave.approve', ['leave_id' => $leave_request->id, 'token' => $approval_token]),
        'disapprove_url' => route('leave.disapprove', ['leave_id' => $leave_request->id, 'token' => $approval_token]),
    ];

    $email = [
        'reporting_manager' => $reporting_manager,
        'hr_emails' => $all_cc_emails, // Now includes HR + 2 additional emails
        'details' => $details,
    ];

    if($reporting_manager) {
        dispatch(new SendLeaveEmail($email));
    }

    return redirect()->route('view_leave_request')->with('success', 'Leave Application Submitted Successfully');
}

public function approveLeave($leave_id, $token)
{
    try {
        $leave = Leave::findOrFail($leave_id);

        // Verify token
        if (!$leave->verifyApprovalToken($token)) {
            return view('leave.action-result', [
                'success' => false,
                'message' => 'Invalid or expired approval link.'
            ]);
        }

        // Check if already processed
        if ($leave->status !== 'Requested') {
            return view('leave.action-result', [
                'success' => false,
                'message' => 'This leave request has already been processed.'
            ]);
        }

        // Update status
        $leave->status = 'Accepted By HR';
        $leave->approved_by_email = request()->ip(); // or get from session if available
        $leave->action_taken_at = now();
        $leave->updated_at = now();
        $leave->save();

        return view('leave.action-result', [
            'success' => true,
            'message' => 'Leave request approved successfully!',
            'leave' => $leave
        ]);

    } catch (Exception $e) {
        return view('leave.action-result', [
            'success' => false,
            'message' => 'An error occurred while processing the request.'
        ]);
    }
}

public function disapproveLeave($leave_id, $token)
{
    try {
        $leave = Leave::findOrFail($leave_id);

        // Verify token
        if (!$leave->verifyApprovalToken($token)) {
            return view('leave.action-result', [
                'success' => false,
                'message' => 'Invalid or expired approval link.'
            ]);
        }

        // Check if already processed
        if ($leave->status !== 'Requested') {
            return view('leave.action-result', [
                'success' => false,
                'message' => 'This leave request has already been processed.'
            ]);
        }

        // Update status
        $leave->status = 'Rejected';
        $leave->approved_by_email = request()->ip(); // or get from session if available
        $leave->action_taken_at = now();
        $leave->updated_at = now();
        $leave->save();

        return view('leave.action-result', [
            'success' => true,
            'message' => 'Leave request disapproved.',
            'leave' => $leave
        ]);

    } catch (Exception $e) {
        return view('leave.action-result', [
            'success' => false,
            'message' => 'An error occurred while processing the request.'
        ]);
    }
}

    public function view_leave_request(){
        $leave_requests=Leave::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        $reporting_manager=User::where('id', auth()->user()->reporting_to)->first();
        if($reporting_manager){
            $reporting_manager_email=$reporting_manager->email;
        }
        else{
            $reporting_manager_email="";
        }
        return view('User.leave', compact('leave_requests', 'reporting_manager_email'));
    }


     public function update_leave_request(Leave $leave,Request $req){

         $leave->subject=$req->subject;
         $leave->description=$req->description;
         $leave->start_date=$req->start_date;
         $leave->end_date=$req->end_date;
         $leave->reporting_manager_email=$req->reporting_manager_email;

        //  dd($leave);
         $leave->update();
          return redirect()->route('view_leave_request')->with('success', 'Leave Application Edited Successfully');

         }

}
