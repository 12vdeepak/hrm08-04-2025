<?php

namespace App\Console\Commands;

use App\Models\ActivityTracker;
use App\Models\User;
use Illuminate\Console\Command;

class UpdateActivityStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update activity status and inactivity periods';

    /**
     * Execute the console command.
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get all users
        $users = User::whereNotIn('role_id', [1])->where('employee_status',1)->get();

        foreach ($users as $user) {
            // Find the latest activity record for the user
            $latest_activity = ActivityTracker::where('user_id', $user->id)
                ->orderBy('activity_time', 'desc')
                ->first();

            if ($latest_activity) {
                // Check if the user is currently active (clocked in)
                if ($latest_activity->activity_type == 'active' && $latest_activity->end_time == null) {
                    // If the user is active and there's no end_time, update the end_time to the current time
                    $latest_activity->end_time = now();
                    $latest_activity->save();
                }
            }
        }

        $this->info('Activity status and inactivity periods updated successfully.');
    }
}
