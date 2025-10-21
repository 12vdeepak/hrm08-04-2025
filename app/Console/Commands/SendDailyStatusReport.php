<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\CheckIn;
use App\Models\Leave;
use App\Models\Holiday;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\DailyUserStatusReportMail;

class SendDailyStatusReport extends Command
{
    protected $signature = 'send:daily-status-report';
    protected $description = 'Send a daily email to HR with absent users and users on approved leave';

    public function handle()
    {
        $today = Carbon::today();

        // Check if it's a weekend
        if ($today->isWeekend()) {
            $this->info("It's a weekend. Skipping report.");
            return;
        }

        // Check if it's a holiday
        $isHoliday = Holiday::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->exists();
        
        if ($isHoliday) {
            $this->info('Today is a holiday. No report sent.');
            return;
        }

        $allUsers = User::select('id', 'name', 'lastname', 'email')
                        ->where('employee_status', 1)
                        ->get();

        $loggedInUsers = CheckIn::whereDate('start_time', $today)
                                ->pluck('user_id')
                                ->toArray();

        $leaveUsers = Leave::where('status', 'Accepted By HR')
                        ->whereDate('start_date', '<=', $today)
                        ->whereDate('end_date', '>=', $today)
                        ->pluck('user_id')
                        ->toArray();

        $absentUsers = $allUsers->reject(function ($user) use ($loggedInUsers, $leaveUsers) {
            return in_array($user->id, $loggedInUsers) || in_array($user->id, $leaveUsers);
        });

        $leaveUserDetails = $allUsers->filter(function ($user) use ($leaveUsers) {
            return in_array($user->id, $leaveUsers);
        });

        // Send report to HR
        $hrEmails = ['hr@quantumitinnovation.com', 'mansi@quantumitinnovation.com'];
        Mail::to($hrEmails)->queue(new DailyUserStatusReportMail($absentUsers, $leaveUserDetails));

        $this->info('HR status report sent successfully.');
    }
    
    }

