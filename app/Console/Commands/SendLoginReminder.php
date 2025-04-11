<?php

namespace App\Console\Commands;

use DB;
use Carbon\Carbon;
use App\Models\Leave;
use Illuminate\Console\Command;
use App\Mail\LoginReminderMail;
use Illuminate\Support\Facades\Mail;

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

        // Get users who are on leave with status "Accepted By HR"
        $onLeaveUserIds = Leave::where('status', 'Accepted By HR')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->pluck('user_id')
            ->toArray();

        // Subquery: get first check-in per user for today
        $checkInsToday = DB::table('check_ins')
            ->select('user_id', DB::raw('MIN(start_time) as first_checkin_time'))
            ->whereDate('created_at', $today)
            ->groupBy('user_id');

        // Main query: active users only
        $usersToNotify = DB::table('users as u')
            ->leftJoinSub($checkInsToday, 'ci', function ($join) {
                $join->on('u.id', '=', 'ci.user_id');
            })
            ->where('u.employee_status', 1)
            ->whereNull('u.deleted_at')
            ->whereNotIn('u.id', $onLeaveUserIds)
            ->where(function ($query) use ($today) {
                $query->whereNull('ci.first_checkin_time') // Not checked in
                    ->orWhere('ci.first_checkin_time', '>', $today->format('Y-m-d') . ' 10:30:00'); // Checked in late
            })
            ->select('u.id', 'u.name', 'u.lastname', 'u.email')
            ->get();

        foreach ($usersToNotify as $user) {
            $fullName = $user->name . ' ' . $user->lastname;
            // Queue the email
            // Mail::to($user->email)->queue(new LoginReminderMail($fullName));
            Mail::to('rashad.quantumitinnovation@gmail.com')->queue(new LoginReminderMail($fullName));
        }
    }
}
