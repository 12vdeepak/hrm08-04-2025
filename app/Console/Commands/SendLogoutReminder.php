<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use App\Models\Leave;
use App\Models\CheckIn;
use App\Models\Holiday;
use Illuminate\Support\Facades\Mail;
use App\Mail\LogoutReminderMail;
use App\Exports\PendingLogoutReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\PendingLogoutReportMail;

class SendLogoutReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:logout-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails to users who have not logged out by 10:00 PM on weekdays and are not on approved leave';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
    
        // Check if it's a weekend
        if ($today->isWeekend()) {
            $this->info('Weekend. No reminders sent.');
            return;
        }
    
        // Check if it's a holiday
        $isHoliday = Holiday::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->exists();
        
        if ($isHoliday) {
            $this->info('Today is a holiday. No reminders sent.');
            return;
        }
    
        if (now()->format('H:i') === '22:00') {
            // Get users who are on leave with status "Accepted By HR"
            $onLeaveUserIds = Leave::where('status', 'Accepted By HR')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->pluck('user_id')
                ->toArray();
    
            // Get users who checked in today but have not logged out (end_time is null)
            $checkedInNotLoggedOutUserIds = CheckIn::whereDate('start_time', $today)
                ->whereNull('end_time')
                ->pluck('user_id')
                ->toArray();
    
            // Main query: active users only
            $usersToNotify = DB::table('users as u')
                ->where('u.employee_status', 1)
                ->whereNull('u.deleted_at')
                ->whereIn('u.id', $checkedInNotLoggedOutUserIds)
                ->whereNotIn('u.id', $onLeaveUserIds)
                ->select('u.id', 'u.name', 'u.lastname', 'u.email')
                ->get();
    
            foreach ($usersToNotify as $user) {
                $fullName = $user->name . ' ' . $user->lastname;
                // Queue the email
                Mail::to($user->email)->queue(new LogoutReminderMail($fullName));
            }
        }
    
        if (now()->format('H:i') === '23:00') {
            $pendingUsers = DB::table('check_ins as ci')
                ->join('users as u', 'ci.user_id', '=', 'u.id')
                ->whereDate('ci.start_time', $today)
                ->whereNull('ci.end_time')
                ->where('u.employee_status', 1)
                ->whereNull('u.deleted_at')
                ->select('u.name', 'u.lastname', 'u.email', 'ci.start_time', 'ci.start_time_location')
                ->get();
            Mail::queue(new PendingLogoutReportMail($pendingUsers));
        }
    }
}
