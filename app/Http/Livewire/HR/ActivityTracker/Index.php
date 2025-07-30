<?php

namespace App\Http\Livewire\HR\ActivityTracker;

use App\Models\User;
use App\Models\ActivityTracker;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $date;
    public $search;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        if(!$this->date){
            $this->date = date('Y-m-d');
        }
    }

    public function render()
    {

        // $paginatedEmployees = User::whereNotIn('role_id', [1])
        //     ->when($this->search, function ($query, $search) {
        //         $query->where(function ($query) use ($search) {
        //             $query->where('name', 'like', "%{$search}%")
        //                 ->orWhere('lastname', 'like', "%{$search}%");
        //         });
        //     })->paginate(2);
            $query = User::query()->whereNotIn('role_id', [1]);
            $query->where('employee_status', 1); // Filter only active employees
            if($this->search){
                $query->where('name', 'like', "%{$this->search}%");
                $query->orWhere('lastname', 'like', "%{$this->search}%");
            }

            $paginatedEmployees = $query->paginate(10);


        return view('livewire.h-r.activity-tracker.index', compact('paginatedEmployees'));
    }

    public function updated($propertyName)
    {
        if($propertyName == 'search' || $propertyName == 'date'){
            $this->resetPage();
        }
    }

    public function getTotalActivityTime($user_id)
    {
        $totalSeconds = ActivityTracker::where('user_id', $user_id)
            ->whereDate('activity_time', $this->date)
            ->selectRaw('SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))) as total_seconds')
            ->value('total_seconds');

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds - ($hours * 3600)) / 60);
        $seconds = $totalSeconds - ($hours * 3600) - ($minutes * 60);
        $formattedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        return $formattedTime;
    }
}

