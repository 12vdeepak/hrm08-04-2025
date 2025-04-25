<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Events\Leave as EventsLeave;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\leaverequest;

class EmployeeLeaveRequestController extends Controller
{
    public function employee_leave_request()
    {
        $raw_leave_requests = Leave::orderBy('created_at', 'desc')->paginate(10);
        $leave_requests = array();
        foreach ($raw_leave_requests as $raw_leave_request) {
            $user = User::where('id', $raw_leave_request->user_id)->first();
            $leave_request = array();
            $leave_request['id'] = $raw_leave_request->id;
            $leave_request['secondary_number'] = $user->secondary_number ?? 'N/A';
            $leave_request['name'] = $user->name . " " . $user->last_name;
            $leave_request['subject'] = $raw_leave_request->subject;
            $leave_request['description'] = $raw_leave_request->description;
            $leave_request['start_date'] = $raw_leave_request->start_date;
            $leave_request['end_date'] = $raw_leave_request->end_date;
            $leave_request['type'] = $raw_leave_request->type;
            $leave_request['status'] = $raw_leave_request->status;
            $leave_request['reporting_manager_email'] = $raw_leave_request->reporting_manager_email;
            $leave_request['reporting_manager_comment'] = $raw_leave_request->reporting_manager_comment;
            $leave_request['hr_comment'] = $raw_leave_request->hr_comment;
            $start_date = new DateTime($leave_request['start_date']);
            $end_date = new DateTime($leave_request['end_date']);
            $interval = $start_date->diff($end_date);
            $leave_request['total_days'] = $interval->days + 1;
            $leave_requests[] = $leave_request;
        }
        return view('HR.employee_leave_request', compact('leave_requests', 'raw_leave_requests'));
    }

    public function reponse_employee_leave_application(Request $request)
    {
        $leave_request = Leave::where('id', $request->id)->first();
        $leave_request->reporting_manager_email = $request->reporting_manager_email;
        $leave_request->hr_comment = $request->hr_comment;
        if ($request->input('submit_button') == 0) {
            $reporting_manager = $request->reporting_manager_email;
            //change as per minal mam's request
            $hr_emails = User::where('name', '=', 'HR')->pluck('email')->toArray();
            $details = [
                'body' => "New Leave Request from " . $leave_request->user->name . " " . $leave_request->user->last_name ?? ' ',
                'name' => $leave_request->user->name . " " . $leave_request->user->last_name ?? ' ',
                'subject' => $leave_request->subject,
                'type' => $leave_request->type,
                'description' => $leave_request->description,
                'start_date' => $leave_request->start_date,
                'end_date' => $leave_request->end_date,
                'status' => "Requested",
            ];
            if ($reporting_manager) {
                Mail::to($reporting_manager)
                    ->cc($hr_emails)
                    ->send(new leaverequest($details));
            }
            $leave_request->status = "Forwarded to Reporting Manager";
        } elseif ($request->input('submit_button') == 1) {
            $leave_request->status = "Accepted By HR";
        } else {
            $leave_request->status = "Declined By HR";
        }
        $leave_request->save();
        return redirect()->route('employee_leave_request');
    }

    public function delete($id)
    {

        // Find the leave request by ID
        $leaveRequest = Leave::find($id);
        // dd($leaveRequest);
        if (!$leaveRequest) {
            return redirect()->route('employee_leave_request')->with('error', 'Leave request not found');
        }

        // Delete the leave request
        $leaveRequest->delete();

        return redirect()->route('employee_leave_request')->with('success', 'Leave request deleted successfully');
    }
}
