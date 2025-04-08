<?php

namespace App\Http\Livewire;

use App\Models\ActivityTracker as ModelsActivityTracker;
use App\Models\CheckIn;
use Livewire\Component;
use Carbon\Carbon;

class ActivityTracker extends Component
{
    public $isCheckedIn = false;
    public $end;

    protected $listeners = ['refreshComponent' => 'mount'];

    public function mount()
    {
        $this->updateActivity();
    }

    public function updateActivity()
    {
        
        // Find the latest activity record for the user on the current date
        $latest_activity = ModelsActivityTracker::where('user_id', auth()->id())
            ->whereDate('activity_time', '=', date('Y-m-d'))
            ->orderBy('activity_time', 'desc')
            ->first();

        // Find the latest check-in record for the user on the current date
        $latest_check_in = CheckIn::where('user_id', auth()->id())
            ->whereDate('start_time', '=', date('Y-m-d'))
            ->orderBy('start_time', 'desc')
            ->first();

        if ($latest_check_in && $latest_check_in->end_time == null) {
            $this->isCheckedIn = true;
            if ($latest_activity && $this->checkTimePassed($latest_activity->end_time)) {
                $latest_activity->update([
                    'end_time' => Carbon::now(),
                ]);
                $this->end = Carbon::now();
            } else {
                $this->createNewActivity();
            }
        } else {
            $this->isCheckedIn = false;
        }
    }

    private function createNewActivity()
    {
        return ModelsActivityTracker::create([
            'user_id' => auth()->id(),
            'activity_time' => Carbon::now(),
            'activity_type' => 'active',
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now(),
        ]);
    }

    private function checkTimePassed($end_time)
    {
        $endDateTimeObj = Carbon::parse($end_time);

        $currentDateTimeObj = Carbon::now();

        $minutesDifference = $currentDateTimeObj->diffInMinutes($endDateTimeObj);

        if ($minutesDifference <= 10) {
            return true;
        } else {
            return false;
        }
    }

    public function render()
    {
        return view('livewire.activity-tracker');
    }
}
