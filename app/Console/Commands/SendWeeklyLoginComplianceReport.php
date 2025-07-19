<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\CheckIn;
use App\Models\Leave;
use App\Models\Holiday;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\WeeklyLoginComplianceReportMail;

class SendWeeklyLoginComplianceReport extends Command
{
    protected $signature = 'send:weekly-login-compliance-report';
    protected $description = 'Send a weekly report to HR of employees not compliant with 9-hour login requirement';

    public function handle()
    {
        $today = Carbon::today();

        // Only run on Friday
        if (!$today->isFriday()) {
            $this->info('Weekly compliance report only runs on Friday.');
            return;
        }

        $startOfWeek = $today->copy()->startOfWeek(); // Monday
        $endOfWeek = $today->copy()->endOfWeek();     // Sunday

        $allUsers = User::where('employee_status', 1)->get();
        $hrEmails = ['hr@quantumitinnovation.com','mansi@quantumitinnovation.com','sanchitha@quantumitinnovation.com'];
        $reportRows = [];

        foreach ($allUsers as $user) {
            $current = $startOfWeek->copy();
            while ($current->lte($endOfWeek)) {
                // Skip weekends
                if (in_array($current->format('l'), ['Saturday', 'Sunday'])) {
                    $current->addDay();
                    continue;
                }

                // Skip holidays
                $isHoliday = Holiday::where('start_date', '<=', $current->toDateString())
                    ->where('end_date', '>=', $current->toDateString())
                    ->exists();
                if ($isHoliday) {
                    $current->addDay();
                    continue;
                }

                // Skip approved leave
                $onLeave = Leave::where('user_id', $user->id)
                    ->where('status', 'Accepted By HR')
                    ->whereDate('start_date', '<=', $current->toDateString())
                    ->whereDate('end_date', '>=', $current->toDateString())
                    ->exists();
                if ($onLeave) {
                    $current->addDay();
                    continue;
                }

                // Get all check-ins for the day
                $checkIns = CheckIn::where('user_id', $user->id)
                    ->whereDate('start_time', $current->toDateString())
                    ->get();

                if ($checkIns->isEmpty()) {
                    $reportRows[] = [
                        'name' => $user->name . ' ' . $user->lastname,
                        'date' => $current->toDateString(),
                        'check_in' => 'Not Checked In',
                        'check_out' => '-',
                        'remarks' => 'Absent',
                    ];
                    $current->addDay();
                    continue;
                }

                // Calculate total time
                $firstCheckIn = $checkIns->sortBy('start_time')->first();
                $lastCheckOut = $checkIns->sortByDesc('end_time')->first();
                $totalSeconds = 0;
                foreach ($checkIns as $ci) {
                    if ($ci->end_time) {
                        $totalSeconds += strtotime($ci->end_time) - strtotime($ci->start_time);
                    } else {
                        $totalSeconds += strtotime(now()) - strtotime($ci->start_time);
                    }
                }
                $hours = floor($totalSeconds / 3600);
                $minutes = floor(($totalSeconds % 3600) / 60);
                $totalHours = $hours + $minutes / 60;

                if ($totalHours < 9) {
                    $reportRows[] = [
                        'name' => $user->name . ' ' . $user->lastname,
                        'date' => $current->toDateString(),
                        'check_in' => $firstCheckIn->start_time ? Carbon::parse($firstCheckIn->start_time)->format('h:i A') : '-',
                        'check_out' => $lastCheckOut->end_time ? Carbon::parse($lastCheckOut->end_time)->format('h:i A') : '-',
                        'remarks' => $totalHours > 0 ? 'Worked ' . number_format($totalHours, 2) . ' hrs' : 'Absent',
                    ];
                }
                $current->addDay();
            }
        }

        if (empty($reportRows)) {
            $this->info('All employees are compliant this week. No email sent.');
            return;
        }

        $body = 'Please find below the weekly login compliance report for employees who did not complete 9 hours on any day:';

        foreach ($hrEmails as $email) {
            Mail::to($email)->queue(new WeeklyLoginComplianceReportMail($reportRows, $body));
        }

        $this->info('Weekly login compliance report sent to HR.');
    }
} 