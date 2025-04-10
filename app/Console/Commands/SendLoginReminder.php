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
    protected $description = 'Send reminder emails to users who logged in after 10:30 AM or not logged in at all and are not on approved leave';

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

        // Get users who logged in today (any time)
        $checkedInUserIds = CheckIn::whereDate('start_time', $today)
            ->pluck('user_id')
            ->toArray();

        // Get users who logged in after 10:30 AM
        $lateCheckedInUserIds = CheckIn::whereDate('start_time', $today)
            ->whereTime('start_time', '>', '10:30:00')
            ->pluck('user_id')
            ->toArray();

        // Get users on leave
        $onLeaveUserIds = Leave::where('status', 'Accepted By HR')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->pluck('user_id')
            ->toArray();

        // Users to notify:
        // 1. Those who checked in late
        // 2. Those who didn't check in at all
        // But exclude users on leave
        $usersToNotify = $allUsers->filter(function ($user) use ($checkedInUserIds, $lateCheckedInUserIds, $onLeaveUserIds) {
            return (
                // Didn't check in OR checked in late
                (!in_array($user->id, $checkedInUserIds) || in_array($user->id, $lateCheckedInUserIds))
                && !in_array($user->id, $onLeaveUserIds)
            );
        });

        foreach ($usersToNotify as $user) {
            $fullName = $user->name . ' ' . $user->lastname;
            Mail::to($user->email)->queue(new LoginReminderMail($fullName));
            $this->info("Queued reminder email to: $fullName ({$user->email})");
        }
    }
}
