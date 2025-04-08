<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\User;
use App\Events\Leave as EventsLeave;
use Illuminate\Http\Request;

class TeamMemberLeaveRequestController extends Controller
{
    public function view_team_member_leave_request(){
        $raw_leave_requests=Leave::orderBy('created_at', 'desc')->get();
        $leave_requests=array();
        foreach($raw_leave_requests as $raw_leave_request){
            $user=User::where('id',$raw_leave_request->user_id)->first();
            if($user->reporting_to==auth()->user()->id){
                $leave_request=array();
                $leave_request['id']=$raw_leave_request->id;
                $leave_request['name']=$user->name." ".$user->last_name;
                $leave_request['subject']=$raw_leave_request->subject;
                $leave_request['description']=$raw_leave_request->description;
                $leave_request['start_date']=$raw_leave_request->start_date;
                $leave_request['end_date']=$raw_leave_request->end_date;
                $leave_request['status']=$raw_leave_request->status;
                $leave_requests[]=$leave_request;
            }
        }
        return view('User.team_member_leave_request', compact('leave_requests'));
    }

    public function approve_team_member_leave_application($id){
        $leave_request=Leave::where('id', $id)->first();
        $leave_request->status="Approved by Project Manager";
        $leave_request->save();
        broadcast(new EventsLeave);
        return redirect()->route('view_team_member_leave_request')->with('success', 'Leave Application Approved Successfully');
    }

    public function reject_team_member_leave_application($id){
        $leave_request=Leave::where('id', $id)->first();
        $leave_request->status="Disapproved by Project Manager";
        $leave_request->save();
        broadcast(new EventsLeave);
        return redirect()->route('view_team_member_leave_request')->with('success', 'Leave Application Rejected Successfully');
    }
}
