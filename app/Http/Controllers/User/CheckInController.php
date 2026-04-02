<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActivityTracker;
use App\Models\CheckIn;
use App\Helpers\ShiftHelper;
use Stevebauman\Location\Facades\Location;

class CheckInController extends Controller
{
    public function user_checkin(Request $request)
    {
        $response = [];
        $user = auth()->user();

        // Get the user's IP address
        $ip = (env('APP_URL') == "http://localhost") ? '103.48.108.74' : request()->ip();

        // Get the user's location
        $location = Location::get($ip)->cityName;
        $response['location'] = $location;

        // Resolve shift type and the correct shift date for right now
        $shift_type  = $user->shift_type ?? 'day';
        $shift_date  = ShiftHelper::resolveShiftDate($shift_type);

        // Find the latest check-in record for the user on the current SHIFT DATE
        $check_in = CheckIn::where('user_id', $user->id)
            ->where('shift_date', $shift_date)
            ->orderBy('start_time', 'desc')
            ->first();

        if ($check_in) {
            if ($check_in->end_time != null) {
                // User has already checked out — create a new check-in record for this shift
                $check_in = new CheckIn();
                $check_in->user_id             = $user->id;
                $check_in->shift_date          = $shift_date;
                $check_in->start_time          = date('Y-m-d H:i:s');
                $check_in->start_time_location = $location;
                $check_in->in_ip_address       = $ip;
                $check_in->save();

                $this->createNewActivity();

                // Calculate total time for this shift date
                $time = $this->calculateShiftTime($user->id, $shift_date);

                $response['button_status'] = 2; // Checked in
                $response['time']          = $time;
            } else {
                // User has already checked in — record the check-out
                $check_in->end_time          = date('Y-m-d H:i:s');
                $check_in->end_time_location = $location;
                $check_in->out_ip_address    = $ip;
                $check_in->save();

                // Calculate total time for this shift date
                $time = $this->calculateShiftTime($user->id, $shift_date);

                $response['button_status'] = 1; // Checked out
                $response['time']          = $time;
            }
        } else {
            // No record yet — first check-in for this shift
            $check_in = new CheckIn();
            $check_in->user_id             = $user->id;
            $check_in->shift_date          = $shift_date;
            $check_in->start_time          = date('Y-m-d H:i:s');
            $check_in->start_time_location = $location;
            $check_in->in_ip_address       = $ip;
            $check_in->save();

            $this->createNewActivity();

            $response['button_status'] = 2; // Checked in
            $response['time']          = 0;
        }

        return $response;
    }

    /**
     * Sum up all completed check-in durations for the given shift date.
     */
    private function calculateShiftTime(int $user_id, string $shift_date): int
    {
        $check_ins = CheckIn::where('user_id', $user_id)
            ->where('shift_date', $shift_date)
            ->get();

        $time = 0;
        foreach ($check_ins as $ci) {
            if ($ci->end_time != null) {
                $time += strtotime($ci->end_time) - strtotime($ci->start_time);
            }
        }
        return $time;
    }

    private function createNewActivity()
    {
        return ActivityTracker::create([
            'user_id'       => auth()->id(),
            'activity_time' => date('Y-m-d H:i:s'),
            'activity_type' => 'active',
            'start_time'    => date('Y-m-d H:i:s'),
            'end_time'      => date('Y-m-d H:i:s'),
        ]);
    }
}
