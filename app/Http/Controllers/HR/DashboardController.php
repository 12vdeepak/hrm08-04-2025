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
use App\Helpers\ShiftHelper;

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

        $user       = auth()->user();
        $shift_type = $user->shift_type ?? 'india';

        // Resolve today's shift date (handles cross-midnight US/Canada shifts)
        $today_shift_date = ShiftHelper::resolveShiftDate($shift_type);

        // Check for any open check-in
        $open_check_in = CheckIn::where('user_id', $user->id)
            ->whereNull('end_time')
            ->orderBy('start_time', 'desc')
            ->first();

        // All check-ins for today's shift date
        $check_ins = CheckIn::where('user_id', $user->id)
            ->where('shift_date', $today_shift_date)
            ->get();

        if ($check_ins->isNotEmpty()) {
            $time = 0;
            foreach ($check_ins as $ci) {
                if ($ci->end_time != null) {
                    $time += strtotime($ci->end_time) - strtotime($ci->start_time);
                } else {
                    $time += strtotime(date('Y-m-d H:i:s')) - strtotime($ci->start_time);
                }
            }
            // Button: if there's an open check-in for TODAY's shift, show Clock Out
            $has_open_for_today = $open_check_in && ($open_check_in->shift_date === $today_shift_date);
            $response['button_status'] = $has_open_for_today ? 2 : 1;
            $response['time'] = $time;
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
