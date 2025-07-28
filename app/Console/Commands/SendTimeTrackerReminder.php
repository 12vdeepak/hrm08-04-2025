<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\TimeTracker;
use Illuminate\Support\Facades\Mail;
use App\Mail\TimeTrackerReminderMail;
use Carbon\Carbon;
use App\Models\Leave;
use App\Models\Holiday;


class SendTimeTrackerReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:time-tracker-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder to users who did not fill 8 hours in time tracker today';

    /**
     * Execute the console command.
     */
public function handle()
{
    $today = Carbon::yesterday()->format('Y-m-d');

    // Check if today is a holiday
    $isHoliday = Holiday::where('start_date', '<=', $today)
        ->where('end_date', '>=', $today)
        ->exists();

    if ($isHoliday) {
        $this->info('Today is a holiday. No reminder emails will be sent.');
        return;
    }

    $activeUsers = User::where('employee_status', 1)->get();
    $reminderUsers = [];

    foreach ($activeUsers as $user) {
        // Check if the user is on approved leave today
        $onLeave = Leave::where('user_id', $user->id)
            ->where('status', 'Accepted By HR')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();

        if ($onLeave) {
            continue; // Skip users on approved leave
        }

        // Get today's time tracker entries
        $entries = TimeTracker::where('user_id', $user->id)
            ->where('work_date', $today)
            ->pluck('work_time');

        $totalMinutes = 0;

        foreach ($entries as $time) {
            if (preg_match('/^(\d{1,2}):(\d{2})$/', $time, $matches)) {
                $hours = (int)$matches[1];
                $minutes = (int)$matches[2];
                $totalMinutes += ($hours * 60 + $minutes);
            }
        }

        // If less than 8 hours, add to reminder list
        if ($totalMinutes < 480) {
            $reminderUsers[] = $user;
        }
    }
     dd($reminderUsers);

    // foreach ($reminderUsers as $user) {
    //     Mail::to($user->email)->queue(new TimeTrackerReminderMail($user));
    // }
    $testEmail = 'deepak.quantumitinnovation@gmail.com';

    foreach ($reminderUsers as $user) {
        Mail::to($testEmail)->queue(new TimeTrackerReminderMail($user));
    }

    $this->info(count($reminderUsers) . ' users reminded to fill 8 hours in Time Tracker.');
}
}
