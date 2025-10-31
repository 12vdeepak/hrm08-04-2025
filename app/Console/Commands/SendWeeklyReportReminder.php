<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Leave;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\WeeklyReportReminder;
use App\Models\Holiday;

class SendWeeklyReportReminder extends Command
{
    protected $signature = 'send:weekly-report-reminder';
    protected $description = 'Send weekly report reminder email to every employee on Friday, and on Thursday for employees on leave on Friday.';

    public function handle()
    {
        $today = Carbon::today();
        $weekday = $today->englishDayOfWeek;

        // Exclude holidays: skip if today is a holiday
        $isHoliday = Holiday::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->exists();
        if ($isHoliday) {
            $this->info('Today is a holiday. Skipping weekly report reminders.');
            return Command::SUCCESS;
        }

        if ($weekday !== 'Thursday' && $weekday !== 'Friday') {
            $this->info('Not Thursday/Friday. Skipping reminder.');
            return Command::SUCCESS;
        }

        $activeUsers = User::where('employee_status', 1)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get();
        $remindersSent = 0;
        $ccAdded = false; // ensure CC is added only once per run

        foreach ($activeUsers as $user) {
            $isOnLeaveFriday = Leave::where('user_id', $user->id)
                ->where('status', 'Accepted By HR')
                ->whereDate('start_date', '<=', $today->copy()->next('Friday'))
                ->whereDate('end_date', '>=', $today->copy()->next('Friday'))
                ->exists();

            // Thursday: only send to users who will be absent Friday
            // Friday: only send to users not absent today
            if (($weekday === 'Thursday' && $isOnLeaveFriday)) {
                $mailable = new WeeklyReportReminder($user, true);
                $mailer = Mail::to($user->email);
                if (!$ccAdded) {
                    $mailer->cc(['nitin@quantumitinnovation.com', 'hr@quantumitinnovation.com']);
                    $ccAdded = true;
                }
                $mailer->queue($mailable);
                $remindersSent++;
            } elseif ($weekday === 'Friday' && !$isOnLeaveFriday) {
                $mailable = new WeeklyReportReminder($user, false);
                $mailer = Mail::to($user->email);
                if (!$ccAdded) {
                    $mailer->cc(['nitin@quantumitinnovation.com', 'hr@quantumitinnovation.com']);
                    $ccAdded = true;
                }
                $mailer->queue($mailable);
                $remindersSent++;
            }
        }
        $this->info("Weekly report reminders sent: {$remindersSent}");
        return Command::SUCCESS;
    }
}
