<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Leave;
use App\Models\Holiday;
use App\Models\ActivityTracker;
use Carbon\Carbon;
use App\Mail\EmployeeActivityReminderMail;

class SendEmployeeActivityReminder extends Command
{
    protected $signature = 'send:employee-activity-reminder';
    protected $description = 'Send reminder emails to employees who have not completed 9 hours of activity';

    public function handle()
    {
        $today = Carbon::today();
        $dayName = $today->format('l');

        // Skip weekends
        if (in_array($dayName, ['Saturday', 'Sunday'])) {
            $this->info('Weekend. No reminders sent.');
            return;
        }

        // Skip holidays
        $isHoliday = Holiday::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->exists();
        if ($isHoliday) {
            $this->info('Holiday. No reminders sent.');
            return;
        }

        $allUsers = User::where('employee_status', 1)->get();
        $remindersSent = 0;

        $usersBelow9Hours = [];
        foreach ($allUsers as $user) {
            // Skip if user doesn't have email
            if (empty($user->email)) {
                continue;
            }

            // Skip if on leave
            $onLeave = Leave::where('user_id', $user->id)
                ->where('status', 'Accepted By HR')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->exists();

            if ($onLeave) {
                continue;
            }

            // Calculate total seconds worked from activity tracker
            $totalSeconds = ActivityTracker::where('user_id', $user->id)
                ->whereDate('activity_time', $today)
                ->selectRaw('SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))) as total_seconds')
                ->value('total_seconds');

            $totalSeconds = $totalSeconds ?? 0;
            $totalHours = round($totalSeconds / 3600, 2);

            // Send reminder if less than 9 hours completed
            if ($totalHours < 9) {
                $remainingHours = round(9 - $totalHours, 2);

                Mail::to($user->email)->queue(new EmployeeActivityReminderMail(
                    $user,
                    $totalHours,
                    $remainingHours,
                    $today
                ));

                $remindersSent++;
                $this->info("Reminder sent to: {$user->name} {$user->lastname} ({$user->email})");
            }
        }

        if ($remindersSent > 0) {
            $this->info("Total reminders sent: {$remindersSent}");
        } else {
            $this->info('All employees have completed their 9 hours. No reminders sent.');
        }
    }
}
