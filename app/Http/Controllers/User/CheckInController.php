<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActivityTracker;
use App\Models\CheckIn;
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

        // Find the latest check-in record for the user on the current date
        $check_in = CheckIn::where('user_id', $user->id)
            ->whereDate('start_time', '=', date('Y-m-d'))
            ->orderBy('start_time', 'desc')
            ->first();

        if ($check_in) {
            if ($check_in->end_time != null) {
                // User has already checked out, create a new check-in record
                $check_in = new CheckIn();
                $check_in->user_id = $user->id;
                $check_in->start_time = date('Y-m-d H:i:s');
                $check_in->start_time_location = $location;
                $check_in->in_ip_address = $ip;
                $check_in->save();

                $this->createNewActivity();

                // Calculate total time for the user on the current date
                $check_ins = CheckIn::where('user_id', $user->id)
                    ->whereDate('start_time', '=', date('Y-m-d'))
                    ->get();

                $time = 0;
                foreach ($check_ins as $check_in) {
                    if ($check_in->end_time != null) {
                        $time += strtotime($check_in->end_time) - strtotime($check_in->start_time);
                    }
                }

                $response['button_status'] = 2; // User checked in
                $response['time'] = $time; // Total time for the user on the current date
            } else {
                // User has already checked in, update the check-in record with the check-out time
                $check_in->end_time = date('Y-m-d H:i:s');
                $check_in->end_time_location = $location;
                $check_in->out_ip_address = $ip;
                $check_in->save();

                // Calculate total time for the user on the current date
                $check_ins = CheckIn::where('user_id', $user->id)
                    ->whereDate('start_time', '=', date('Y-m-d'))
                    ->get();

                $time = 0;
                foreach ($check_ins as $check_in) {
                    if ($check_in->end_time != null) {
                        $time += strtotime($check_in->end_time) - strtotime($check_in->start_time);
                    }
                }

                $response['button_status'] = 1; // User checked out
                $response['time'] = $time; // Total time for the user on the current date
            }
        } else {
            // User hasn't checked in yet, create a new check-in record
            $check_in = new CheckIn();
            $check_in->user_id = $user->id;
            $check_in->start_time = date('Y-m-d H:i:s');
            $check_in->start_time_location = $location;
            $check_in->in_ip_address = $ip;
            $check_in->save();

            $this->createNewActivity();

            $response['button_status'] = 2; // User checked in
            $response['time'] = 0; // Total time for the user on the current date
        }

        // $this->activityTracker($user);

        return $response;
    }

    private function activityTracker($user){
        // Find the latest activity record for the user on the current date
        $latest_activity = ActivityTracker::where('user_id', $user->id)
            ->whereDate('activity_time', '=', date('Y-m-d'))
            ->orderBy('activity_time', 'desc')
            ->first();

        if ($latest_activity && $latest_activity->end_time == null) {
            // The user is already marked as active, so update the end_time to indicate inactivity.
            $latest_activity->end_time = date('Y-m-d H:i:s');
            $latest_activity->save();
        }else{
            // Update the activity tracker for the current check-in
            $activity = new ActivityTracker();
            $activity->user_id = $user->id;
            $activity->activity_time = date('Y-m-d H:i:s');
            $activity->activity_type = 'active';
            $activity->start_time = date('Y-m-d H:i:s'); // Set the start_time for the current activity
            $activity->save();
        }

    }

    private function createNewActivity(){
        return ActivityTracker::create([
            'user_id' => auth()->id(),
            'activity_time' => date('Y-m-d H:i:s'),
            'activity_type' => 'active',
            'start_time' => date('Y-m-d H:i:s'),
            'end_time' => date('Y-m-d H:i:s'),
        ]);
    }
}
