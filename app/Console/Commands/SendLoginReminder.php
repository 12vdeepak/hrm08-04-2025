<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\CheckIn;
use App\Models\Leave;
use App\Mail\LoginReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendLoginReminder extends Command
{
    protected $signature = 'send:login-reminder';
    protected $description = 'Send reminder emails at 11:00 AM to users who logged in after 10:30 AM or have not logged in yet';

    public function handle()
    {
        Log::info('Starting SendLoginReminder command');

        $today = Carbon::today();
        $now = Carbon::now();
        Log::info('Today: ' . $today->toDateString() . ', Current time: ' . $now->toTimeString());

        // Check if it's a weekend
        if ($today->isWeekend()) {
            Log::info('Weekend detected. No reminders will be processed.');
            $this->info('Weekend. No reminders sent.');
            return;
        }

        // Check if current time is around 11:00 AM (when reminders should be sent)
        $reminderTime = Carbon::today()->setTime(11, 0, 0);
        Log::info('Reminder time set to: ' . $reminderTime->toTimeString());

        // Optional: Only run this command if it's close to 11:00 AM (within 5 minutes)
        if ($now->lt($reminderTime->copy()->subMinutes(5)) || $now->gt($reminderTime->copy()->addMinutes(5))) {
            Log::info('Current time is not within the reminder window (11:00 AM ±5 minutes). Skipping reminder process.');
            $this->info('Current time is not within reminder window. No reminders sent.');
            return;
        }

        Log::info('Retrieving active users');
        // Get all active users
        $allUsers = User::select('id', 'name', 'lastname', 'email')
            ->where('employee_status', 1)
            ->get();
        Log::info('Found ' . $allUsers->count() . ' active users');

        Log::info('Retrieving users who checked in before or at 10:30 AM (on time)');
        // Get users who have checked in on time (before or at 10:30 AM)
        $onTimeUsers = CheckIn::whereDate('start_time', $today)
            ->whereTime('start_time', '<=', '10:30:00')
            ->pluck('user_id')
            ->toArray();
        Log::info('Found ' . count($onTimeUsers) . ' users who checked in on time');

        Log::info('Retrieving users who checked in late (after 10:30 AM)');
        // Get users who have checked in late (after 10:30 AM)
        $lateUsers = CheckIn::whereDate('start_time', $today)
            ->whereTime('start_time', '>', '10:30:00')
            ->pluck('user_id')
            ->toArray();
        Log::info('Found ' . count($lateUsers) . ' users who checked in late');

        // All users who have checked in (on time or late)
        $checkedInUsers = array_merge($onTimeUsers, $lateUsers);
        Log::info('Total users checked in: ' . count($checkedInUsers));

        Log::info('Retrieving users on approved leave');
        // Get users who are on leave with status "Accepted By HR"
        $onLeaveUserIds = Leave::where('status', 'Accepted By HR')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->pluck('user_id')
            ->toArray();
        Log::info('Found ' . count($onLeaveUserIds) . ' users on approved leave');

        // Users who haven't checked in at all
        $noCheckInUsers = $allUsers->reject(function ($user) use ($checkedInUsers) {
            return in_array($user->id, $checkedInUsers);
        })->pluck('id')->toArray();
        Log::info('Found ' . count($noCheckInUsers) . ' users who haven\'t checked in at all');

        // Users who checked in late or haven't checked in yet, and aren't on leave
        $usersToNotify = $allUsers->filter(function ($user) use ($lateUsers, $noCheckInUsers, $onLeaveUserIds) {
            return (in_array($user->id, $lateUsers) || in_array($user->id, $noCheckInUsers)) &&
                !in_array($user->id, $onLeaveUserIds);
        });
        Log::info('Found ' . $usersToNotify->count() . ' users to notify (late or absent, not on leave)');

        // Log users who will be notified
        if ($usersToNotify->isEmpty()) {
            Log::info('No users need to be notified - all users are on time or on leave');
            $this->info('No users need to be notified');
        } else {
            foreach ($usersToNotify as $user) {
                $fullName = $user->name . ' ' . $user->lastname;
                $status = in_array($user->id, $lateUsers) ? "late check-in" : "no check-in";

                // Log instead of sending email
                Log::info("Would send reminder email to: $fullName ({$user->email}) - Status: $status");
                $this->info("Logged reminder for: $fullName ({$user->email}) - Status: $status");

                // Mail is commented out for testing purposes
                // Mail::to($user->email)->queue(new LoginReminderMail($fullName));
            }
        }

        Log::info('SendLoginReminder command completed');
    }
}
