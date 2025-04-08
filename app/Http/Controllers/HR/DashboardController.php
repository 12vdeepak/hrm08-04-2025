<?php

namespace App\Http\Controllers\HR;

use App\Events\Leave as EventsLeave;
use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Department;
use App\Models\Leave;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\EmployeeApplicationTime;
use Stevebauman\Location\Facades\Location as IPLocation;

class DashboardController extends Controller
{
    public function hr_dashboard()
    {
        $employees = User::where('role_id', '!=', 1)->orderBy('created_at', 'desc')->paginate(5);
        $count = array();
        $count['employees'] = User::where('role_id', '!=', 1)->count();
        $count['departments'] = Department::count();
        $count['locations'] = Location::count();
        $response = array();
        // $application_time=EmployeeApplicationTime::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->first();
        // $response['application_time']=$application_time->time;
        $user = auth()->user();
        $check_in = CheckIn::where('user_id', $user->id)->whereDate('start_time', '=', date('Y-m-d'))->orderBy('start_time', 'desc')->first();
        if ($check_in) {
            if ($check_in->end_time != null) {
                $check_ins = CheckIn::where('user_id', $user->id)->whereDate('start_time', '=', date('Y-m-d'))->get();
                $time = 0;
                foreach ($check_ins as $check_in) {
                    if ($check_in->end_time != null) {
                        $time = $time + strtotime($check_in->end_time) - strtotime($check_in->start_time);
                    } else {
                        $time = $time + strtotime(date('Y-m-d H:i:s')) - strtotime($check_in->start_time);
                    }
                }
                $response['button_status'] = 1;
                $response['time'] = $time;
            } else {
                $check_ins = CheckIn::where('user_id', $user->id)->whereDate('start_time', '=', date('Y-m-d'))->get();
                $time = 0;
                foreach ($check_ins as $check_in) {
                    if ($check_in->end_time != null) {
                        $time = $time + strtotime($check_in->end_time) - strtotime($check_in->start_time);
                    } else {
                        $time = $time + strtotime(date('Y-m-d H:i:s')) - strtotime($check_in->start_time);
                    }
                }
                $response['button_status'] = 2;
                $response['time'] = $time;
            }
        } else {
            $response['button_status'] = 1;
            $response['time'] = 0;
        }
        return view('HR.dashboard', compact('employees', 'count', 'response'));
    }

    public function changePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'cpassword' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('openModal', 'changepasswordnmodal');
        }
        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->view_password = $request->password;
        $user->save();

        return redirect()->route('hr_dashboard')->with('success', 'Password Updated Successfully!');
    }

    public function markasallread()
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();
        return redirect()->route('hr_dashboard');
    }
}
