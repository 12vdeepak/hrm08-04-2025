<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\FestivalGreeting;
use App\Models\Holiday;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendFestivalGreetings extends Command
{
    protected $signature = 'festival:send-greetings';
    protected $description = 'Send festival greetings to all active employees';

    public function handle()
    {
        $today = Carbon::today();

        // Get upcoming holidays (starting today or in next 2 days)
        $holidays = Holiday::whereDate('start_date', '>=', $today)
            ->whereDate('start_date', '<=', $today->copy()->addDays(2))
            ->get();

        if ($holidays->isEmpty()) {
            $this->info('No upcoming holidays found.');
            return;
        }

        // Log all upcoming holidays
        $this->info("Upcoming holidays:");
        foreach ($holidays as $holiday) {
            $this->info("Holiday: {$holiday->name}, Start Date: {$holiday->start_date}, End Date: {$holiday->end_date}");
        }

        // Get all active employees
        $employees = User::where('employee_status', 1)->get();

        if ($employees->isEmpty()) {
            $this->info('No active employees found.');
            return;
        }

        // Log all active employees
        $this->info("Active employees:");
        foreach ($employees as $employee) {
            $this->info("Employee: {$employee->name}, Email: {$employee->email}");
        }

        // Send to testing email
        $testingEmail = 'deepak.quantumitinnovation@gmail.com';

        foreach ($holidays as $holiday) {
            foreach ($employees as $employee) {
                // Send the email with festival greetings to the testing email
                Mail::to($testingEmail)
                    ->send(new FestivalGreeting(
                        $holiday->name,
                        $employee->name,
                        \Carbon\Carbon::parse($holiday->start_date)->format('M j, Y'),
                        \Carbon\Carbon::parse($holiday->end_date)->format('M j, Y')
                    ));

                // Log each email sent
                $this->info("Sent {$holiday->name} greeting to testing email: {$testingEmail}");
            }
        }

        $this->info('Festival greetings sent successfully!');
    }
}
