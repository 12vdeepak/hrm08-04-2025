<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\FestivalGreeting;
use App\Models\Holiday;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

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
                $retries = 3;
                $sent = false;

                while ($retries > 0 && !$sent) {
                    try {
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
                        $sent = true;  // Email sent successfully
                    } catch (TransportExceptionInterface $e) {
                        // Check if the error is due to rate limiting
                        if (strpos($e->getMessage(), '421 too many messages in this connection') !== false) {
                            $this->info("Rate limit hit. Retrying in 10 seconds...");
                            sleep(10);  // Retry after a delay
                            $retries--;
                        } else {
                            // Log the error and stop retrying for other errors
                            $this->info("Failed to send email: {$e->getMessage()}");
                            $retries = 0;  // Stop retrying
                        }
                    }
                }

                if (!$sent) {
                    $this->info("Failed to send greeting to {$employee->name} after retries.");
                }

                // Optional: Add a small delay to avoid hitting the SMTP server too quickly
                sleep(1);  // Adjust the delay as necessary
            }
        }

        $this->info('Festival greetings sent successfully!');
    }
}
