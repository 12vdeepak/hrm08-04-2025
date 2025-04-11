<?php

namespace App\Console\Commands;

use DB;
use Carbon\Carbon;
use App\Models\Leave;
use Illuminate\Console\Command;
use App\Mail\LateMarkReminderMail;
use Illuminate\Support\Facades\Mail;

class SendLateMarkReminder extends Command
{
    protected $signature = 'send:late-mark-reminder';
    protected $description = 'Send email at 8 PM to users who checked in after 11 AM and are not on approved leave';

    public function handle()
    {
        $today = Carbon::today();
        $elevenAM = $today->copy()->setTime(11, 0, 0);

        if ($today->isWeekend()) {
            $this->info('Weekend. No reminders sent.');
            return;
        }

        // Users on leave
        $onLeaveUserIds = Leave::where('status', 'Accepted By HR')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->pluck('user_id')
            ->toArray();

        // Get first check-ins after 11:00 AM
        $checkInsToday = DB::table('check_ins')
            ->select('user_id', DB::raw('MIN(start_time) as first_checkin_time'))
            ->whereDate('created_at', $today)
            ->groupBy('user_id');

        // Get users who checked in late OR didn’t check in
        $usersToNotify = DB::table('users as u')
            ->leftJoinSub($checkInsToday, 'ci', function ($join) {
                $join->on('u.id', '=', 'ci.user_id');
            })
            ->where('u.employee_status', 1)
            ->whereNull('u.deleted_at')
            ->whereNotIn('u.id', $onLeaveUserIds)
            ->where(function ($query) use ($elevenAM) {
                $query->whereNull('ci.first_checkin_time') // not logged in
                    ->orWhere('ci.first_checkin_time', '>', $elevenAM); // logged in late
            })
            ->select('u.id', 'u.name', 'u.lastname', 'u.email', 'ci.first_checkin_time')
            ->get();

        foreach ($usersToNotify as $user) {
            $fullName = $user->name . ' ' . $user->lastname;

            Mail::to($user->email)->queue(new LateMarkReminderMail($fullName));
        }

    }
}
