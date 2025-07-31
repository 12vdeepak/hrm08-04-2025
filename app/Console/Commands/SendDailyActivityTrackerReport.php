<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Leave;
use App\Models\Holiday;
use App\Models\ActivityTracker;
use Carbon\Carbon;
use App\Mail\DailyActivityTrackerReportMail;

class SendDailyActivityTrackerReport extends Command
{
    protected $signature = 'send:daily-activity-tracker-report';
    protected $description = 'Send a daily report of employees who did not complete 9 hours of activity';

    public function handle()
    {
        $today = Carbon::today();
        $dayName = $today->format('l');

        // Skip weekends
        if (in_array($dayName, ['Saturday', 'Sunday'])) {
            $this->info('Weekend. No report sent.');
            return;
        }

        // Skip holidays
        $isHoliday = Holiday::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->exists();
        if ($isHoliday) {
            $this->info('Holiday. No report sent.');
            return;
        }

        $allUsers = User::where('employee_status', 1)->get();
        $hrEmails = ['hr@quantumitinnovation.com', 'mansi@quantumitinnovation.com', 'sanchitha@quantumitinnovation.com'];
        $reportRows = [];

        foreach ($allUsers as $user) {
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

            if ($totalHours < 9) {
                $reportRows[] = [
                    'name' => $user->name . ' ' . $user->lastname,
                    'date' => $today->toDateString(),
                    'worked_hours' => $totalHours,
                    'status' => $totalHours > 0 ? "Worked {$totalHours} hrs" : 'Absent',
                ];
            }
        }

        // Dump results for testing before sending email
        dd($reportRows);

        if (empty($reportRows)) {
            $this->info('All employees are compliant today. No email sent.');
            return;
        }

        $body = "Please find below the daily report of employees who did not complete 9 hours of activity on " . $today->toFormattedDateString() . ".";

        Mail::to($hrEmails)->queue(new DailyActivityTrackerReportMail($reportRows, $body));

        $this->info('Daily activity tracker report sent to HR.');
    }
}
