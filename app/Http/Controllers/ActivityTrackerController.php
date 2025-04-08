<?php

namespace App\Http\Controllers;

use App\Models\ActivityTracker;
use App\Models\CheckIn;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityTrackerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($date = null)
    {
        $employees = User::whereNotIn('role_id', [1])->paginate(10);
        if($date == null){
            $date = date('Y-m-d');
        }

        $mappedEmployees = $employees->map(function ($employee) use($date){
            return [
                'data' => $employee,
                'total_time' => $this->getTotalActivityTime($employee->id, $date),
            ];
        });

        // Create a new paginated collection using the mapped result and restore pagination links
        $paginatedEmployees = new LengthAwarePaginator(
    $mappedEmployees,
    $employees->total(),
    $employees->perPage(),
    $employees->currentPage(),
    ['path' => route('activity_tracker.index')] // Replace 'your.route.name' with the actual route name for pagination links
        );

        return view('HR.activity-tracker.index', compact('paginatedEmployees', 'date'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, $date = null)
    {
        
        if($date == null){
            $date = date('Y-m-d');
        }

        $activities  = ActivityTracker::where('user_id', $id)->whereDate('activity_time', '=', $date)->get();
        // dd($activities);
        $mappedActivities = $activities->map(function ($activity) use($date){
            return [
                'activity' => $activity,
                'total_time' => $this->getSubTotalTime($activity->start_time, $activity->end_time),
            ];
        });

        return view('HR.activity-tracker.show', compact('mappedActivities', 'date', 'id'));


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function updateActivity()
    {
        // Find the latest activity record for the user on the current date
        $latest_activity = ActivityTracker::where('user_id', auth()->id())
            ->whereDate('activity_time', '=', date('Y-m-d'))
            ->orderBy('activity_time', 'desc')
            ->first();
        // Find the latest check-in record for the user on the current date
        $latest_check_in = CheckIn::where('user_id', auth()->id())
            ->whereDate('start_time', '=', date('Y-m-d'))
            ->orderBy('start_time', 'desc')
            ->first();

        $data = [];

        if ($latest_check_in && $latest_check_in->end_time == null) {
            $data['is_checked_in'] = true;
            if ($latest_activity && $this->checkTimePassed($latest_activity->end_time)){
                $latest_activity->update([
                    'end_time' => date('Y-m-d H:i:s'),
                ]);
            }else{
                $this->createNewActivity();
            }
        } else {
            $data['is_checked_in'] = false;
        }

        return $data;
    }

    private function createNewActivity()
    {
        return ActivityTracker::create([
            'user_id' => auth()->id(),
            'activity_time' => date('Y-m-d H:i:s'),
            'activity_type' => 'active',
            'start_time' => date('Y-m-d H:i:s'),
            'end_time' => date('Y-m-d H:i:s'),
        ]);
    }

    private function checkTimePassed($end_time)
    {
        $endDateTimeObj = Carbon::parse($end_time);

        $currentDateTimeObj = Carbon::now();

        $minutesDifference = $currentDateTimeObj->diffInMinutes($endDateTimeObj);

        if ($minutesDifference <= 5) {
            return true;
        } else {
            return false;
        }
    }

    private function getTotalActivityTime($user_id, $date){
        
        $acitivities  = ActivityTracker::where('user_id', $user_id)->whereDate('activity_time', '=', $date)->get();
        // dd($acitivities);    
        $totalSeconds = 0;
        foreach ($acitivities as $entry) {
            // Calculate the time difference between start_time and end_time for each entry.
            $startTime = strtotime($entry->start_time);
            $endTime = strtotime($entry->end_time);
            $secondsDiff = $endTime - $startTime;

            // Accumulate the total active time in seconds.
            $totalSeconds += $secondsDiff;
        }

        // Step 4: Format the total active time in the desired format (hours:minutes:seconds).
        $hours = floor($totalSeconds / 3600);
        
        $minutes = floor(($totalSeconds - ($hours * 3600)) / 60);
        $seconds = $totalSeconds - ($hours * 3600) - ($minutes * 60);
        $formattedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        return $formattedTime;
    }

    private function getSubTotalTime($start_time, $end_time) {
        $startDateTime = strtotime($start_time);
        $endDateTime = strtotime($end_time);
    
        // Check if the timestamps are valid
        if ($startDateTime === false || $endDateTime === false) {
            return '00:00:00'; // Return 0 if the timestamps are invalid
        }
    
        // Check if the start time is before the end time
        if ($startDateTime >= $endDateTime) {
            return '00:00:00'; // Return 0 if the start time is not before the end time
        }
    
        $totalSeconds = $endDateTime - $startDateTime;
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds - ($hours * 3600)) / 60);
        $seconds = $totalSeconds - ($hours * 3600) - ($minutes * 60);
    
        $formattedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        return $formattedTime;
    }
    
    
    
    
}
