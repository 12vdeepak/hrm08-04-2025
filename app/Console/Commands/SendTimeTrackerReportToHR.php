<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\TimeTracker;
use App\Models\Leave;
use App\Models\Holiday;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeeklyTimeTrackerHRReportMail;
use Carbon\Carbon;

class SendTimeTrackerReportToHR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:weekly-tracker-report';
    protected $description = 'Send weekly report of users who didn’t complete 8 hours';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startDate = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endDate = Carbon::now()->endOfWeek(Carbon::FRIDAY);

        $activeUsers = User::where('employee_status', 1)->get();
        $report = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $currentDate = $date->format('Y-m-d');

            // Skip holiday
            $isHoliday = Holiday::where('start_date', '<=', $currentDate)
                ->where('end_date', '>=', $currentDate)
                ->exists();

            if ($isHoliday) continue;

            foreach ($activeUsers as $user) {
                // Skip user if on leave
                $onLeave = Leave::where('user_id', $user->id)
                    ->where('status', 'Accepted By HR')
                    ->whereDate('start_date', '<=', $currentDate)
                    ->whereDate('end_date', '>=', $currentDate)
                    ->exists();

                if ($onLeave) continue;

                // Get work_time entries
                $entries = TimeTracker::where('user_id', $user->id)
                    ->where('work_date', $currentDate)
                    ->pluck('work_time');

                $totalMinutes = 0;
                foreach ($entries as $time) {
                    if (preg_match('/^(\d{1,2}):(\d{2})$/', $time, $matches)) {
                        $totalMinutes += $matches[1] * 60 + $matches[2];
                    }
                }

                if ($totalMinutes < 480) {
                    $report[] = [
                        'date' => $currentDate,
                        'name' => $user->name,
                        'email' => $user->email,
                        'total_time' => sprintf('%02d:%02d', floor($totalMinutes / 60), $totalMinutes % 60),
                    ];
                }
            }
        }

        if (!empty($report)) {
            // Send to HR
            Mail::to('deepak.quantumitinnovation@gmail.com') // change this
                ->send(new WeeklyTimeTrackerHRReportMail($report));
            $this->info('Weekly time tracker report sent to HR.');
        } else {
            $this->info('All employees completed their time tracker for the week.');
        }
    }
}
