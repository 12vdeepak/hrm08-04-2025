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
        $user      = auth()->user();

        // Get the user's IP and location
        $ip           = (env('APP_URL') == "http://localhost") ? '103.48.108.74' : request()->ip();
        $locationData = Location::get($ip);
        $location     = ($locationData && $locationData->cityName) ? $locationData->cityName : 'Unknown';
        $response['location'] = $location;

        $shift_type = $user->shift_type ?? 'india';

        /*
         * CHECKOUT CHECK (highest priority):
         * If the user has ANY open check-in (end_time IS NULL),
         * record the checkout on that record — regardless of what
         * time it is or which calendar date we are on.
         * This allows an employee to check in at 9 AM and check out
         * at 1 AM the next day without the system creating a new
         * check-in by mistake.
         */
        $open_check_in = CheckIn::where('user_id', $user->id)
            ->whereNull('end_time')
            ->orderBy('start_time', 'desc')
            ->first();

        if ($open_check_in) {
            // Close the open check-in
            $open_check_in->end_time          = date('Y-m-d H:i:s');
            $open_check_in->end_time_location = $location;
            $open_check_in->out_ip_address    = $ip;
            $open_check_in->save();

            // Calculate total hours for the shift_date of the record just closed
            $time = $this->calculateShiftTime($user->id, $open_check_in->shift_date);

            $response['button_status'] = 1; // Checked out
            $response['time']          = $time;

            return $response;
        }

        /*
         * NEW CHECK-IN:
         * No open record found → the employee is clocking in.
         * Resolve the correct shift_date from ShiftHelper so that
         * a US/Canada employee checking in at (e.g.) 11 PM is recorded
         * on the correct shift date even if it's technically tomorrow IST.
         */
        $shift_date = ShiftHelper::resolveShiftDate($shift_type);

        $check_in = new CheckIn();
        $check_in->user_id             = $user->id;
        $check_in->shift_date          = $shift_date;
        $check_in->start_time          = date('Y-m-d H:i:s');
        $check_in->start_time_location = $location;
        $check_in->in_ip_address       = $ip;
        $check_in->save();

        $this->createNewActivity();

        $response['button_status'] = 2; // Checked in
        $response['time']          = $this->calculateShiftTime($user->id, $shift_date);

        return $response;
    }

    /**
     * Sum all completed check-in durations for a given shift date.
     */
    private function calculateShiftTime(int $user_id, string $shift_date): int
    {
        $check_ins = CheckIn::where('user_id', $user_id)
            ->where('shift_date', $shift_date)
            ->get();

        $time = 0;
        foreach ($check_ins as $ci) {
            if ($ci->end_time !== null) {
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
