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
         * NEW SHIFT-DATE AWARE LOGIC:
         * 1. Resolve today's shift date.
         * 2. Find ANY open check-in (end_time IS NULL).
         * 3. If that open check-in's shift_date MATCHES today's resolved shift date:
         *    -> This is a valid "Check Out" action for the current shift.
         * 4. If that open check-in is from a PAST shift date:
         *    -> Auto-close it (end_time = start_time) and proceed to NEW check-in.
         */
        $shift_date = ShiftHelper::resolveShiftDate($shift_type);

        $open_check_in = CheckIn::where('user_id', $user->id)
            ->whereNull('end_time')
            ->orderBy('start_time', 'desc')
            ->first();

        if ($open_check_in) {
            if ($open_check_in->shift_date === $shift_date) {
                // VALID CHECK-OUT for today's shift
                $open_check_in->end_time          = date('Y-m-d H:i:s');
                $open_check_in->end_time_location = $location;
                $open_check_in->out_ip_address    = $ip;
                $open_check_in->save();

                $time = $this->calculateShiftTime($user->id, $open_check_in->shift_date);
                $response['button_status'] = 1; // Show Clock In
                $response['time']          = $time;
                return $response;
            } else {
                // STALE OPEN RECORD (from yesterday or earlier)
                // Just close it silently so it doesn't poison the time calculation
                $open_check_in->end_time = $open_check_in->start_time;
                $open_check_in->save();
                // Proceed to NEW check-in logic below
            }
        }

        /*
         * NEW CHECK-IN (Either no open record, or we just closed a stale one)
         */
        $check_in = new CheckIn();
        $check_in->user_id             = $user->id;
        $check_in->shift_date          = $shift_date;
        $check_in->start_time          = date('Y-m-d H:i:s');
        $check_in->start_time_location = $location;
        $check_in->in_ip_address       = $ip;
        $check_in->save();

        $this->createNewActivity();

        $response['button_status'] = 2; // Show Clock Out
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
