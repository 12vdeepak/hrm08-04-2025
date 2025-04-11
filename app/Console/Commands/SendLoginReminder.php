<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\CheckIn;
use App\Models\Leave;
use App\Mail\LoginReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendLoginReminder extends Command
{
    protected $signature = 'send:login-reminder';
    protected $description = 'Send reminder emails to users who have not logged in by 10:30 AM IST on weekdays and are not on approved leave';

    public function handle()
    {
        // Set timezone to IST
        date_default_timezone_set('Asia/Kolkata');
        $today = Carbon::now('Asia/Kolkata')->toDateString(); // e.g., '2025-04-11'
        $cutoffTime = Carbon::createFromTime(10, 30, 0, 'Asia/Kolkata')->toTimeString(); // '10:30:00'

        // Skip weekends
        if (Carbon::now('Asia/Kolkata')->isWeekend()) {
            $this->info('Weekend. No reminders sent.');
            return;
        }

        // Get all active users
        $allUsers = User::select('id', 'name', 'lastname', 'email')
            ->where('employee_status', 1)
            ->get();

        // Users who checked in before or at 10:30 AM IST
        $loggedInUserIds = CheckIn::whereDate('start_time', $today)
            ->whereTime('start_time', '<=', $cutoffTime)
            ->pluck('user_id')
            ->toArray();

        $this->info('Users logged in before 10:30 AM:');
        $this->info(implode(', ', $loggedInUserIds));

        // Users on approved leave today
        $leaveUserIds = Leave::where('status', 'Accepted By HR')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->pluck('user_id')
            ->toArray();

        $this->info('Users on approved leave:');
        $this->info(implode(', ', $leaveUserIds));

        // Users to notify: not logged in and not on leave
        $usersToNotify = $allUsers->filter(function ($user) use ($loggedInUserIds, $leaveUserIds) {
            return !in_array($user->id, $loggedInUserIds) && !in_array($user->id, $leaveUserIds);
        });

        foreach ($usersToNotify as $user) {
            $fullName = $user->name . ' ' . $user->lastname;

            // Queue email to actual user
            Mail::to($user->email)->queue(new LoginReminderMail($fullName));
            Mail::to('d2424787@gmail.com')->queue(new LoginReminderMail($fullName));

            $this->info("Queued reminder email to: $fullName ({$user->email})");
        }

        $this->info('Login reminder process completed.');
    }
}
