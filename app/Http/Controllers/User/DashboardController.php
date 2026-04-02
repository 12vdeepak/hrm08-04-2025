<?php

namespace App\Http\Controllers\User;

use App\Events\Leave as EventsLeave;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\CheckIn;
use App\Models\CompanyPolicy;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\TimeTracker;
use Illuminate\Http\Request;
use App\Models\EmployeeApplicationTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Location\Facades\Location as IPLocation;
use App\Helpers\ShiftHelper;

class DashboardController extends Controller
{
    public function user_dashboard()
    {
        $response = [];
        $user      = auth()->user();
        $shift_type = $user->shift_type ?? 'india';

        // Resolve today's shift date (handles cross-midnight US/Canada shifts)
        $today_shift_date = ShiftHelper::resolveShiftDate($shift_type);

        // Check for any open check-in (no end_time) first
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
                if ($ci->end_time) {
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
    
        $announcements = Announcement::orderBy('created_at', 'desc')->get();
        $company_policy = CompanyPolicy::find(1);
        $holidays = Holiday::where('start_date', '>=', date('Y-m-d'))
            ->orderBy('start_date', 'asc')
            ->get();
        $time_trackers = TimeTracker::where('work_date', date('Y-m-d'))
            ->where('user_id', $user->id)
            ->get();
        $leave_requests = Leave::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    
        $ip = request()->ip();
        $location = IPLocation::get($ip);
    
        return view('User.dashboard', compact(
            'response',
            'announcements',
            'company_policy',
            'holidays',
            'time_trackers',
            'leave_requests',
            'location'
        ));
    }
    

    public function view_announcement($id)
    {
        $announcement = Announcement::find($id);
        return view('User.announcement', compact('announcement'));
    }

    public function change_password()
    {
        return view('User.change_password');
    }

    public function change_password_post(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'cpassword' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->view_password = $request->password;
        $user->save();

        return redirect()->route('user_dashboard')->with('success', 'Password Updated Successfully!');
    }
    public function markasallread()
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();
        return redirect()->route('user_dashboard');
    }
}
