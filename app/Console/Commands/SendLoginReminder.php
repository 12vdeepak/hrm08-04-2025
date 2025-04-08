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
    protected $description = 'Send reminder emails to users who have not logged in by 10:30 AM on weekdays and are not on approved leave';

    public function handle()
    {
        $today = Carbon::today();

        if ($today->isWeekend()) {
            $this->info('Weekend. No reminders sent.');
            return;
        }

        // Get all active users
        $allUsers = User::select('id', 'name', 'lastname', 'email')
                        ->where('employee_status', 1)
                        ->get();

        // Get users who have already checked in before 10:30 AM
        $loggedInUsers = CheckIn::whereDate('start_time', $today)
                                ->whereTime('start_time', '<=', '10:30:00')
                                ->pluck('user_id')
                                ->toArray();

        // Get users who are on leave with status "Accepted By HR"
        $onLeaveUserIds = Leave::where('status', 'Accepted By HR')
                               ->whereDate('start_date', '<=', $today)
                               ->whereDate('end_date', '>=', $today)
                               ->pluck('user_id')
                               ->toArray();

        // Filter users who haven't logged in and are not on leave
        $usersToNotify = $allUsers->reject(function ($user) use ($loggedInUsers, $onLeaveUserIds) {
            return in_array($user->id, $loggedInUsers) || in_array($user->id, $onLeaveUserIds);
        });

        foreach ($usersToNotify as $user) {
            $fullName = $user->name . ' ' . $user->lastname;

            // Queue the email
            Mail::to($user->email)->queue(new LoginReminderMail($fullName));

            $this->info("Queued reminder email to: $fullName ({$user->email})");
        }
    }
}
