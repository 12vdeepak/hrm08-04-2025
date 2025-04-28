<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\FestivalGreeting;
use App\Models\Holiday;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendFestivalGreetings extends Command
{
    protected $signature = 'festival:send-greetings {--type=greeting : Type of email to send (greeting/reminder)}';
    protected $description = 'Send festival greetings or reminders to all active employees';

    public function handle()
    {
        $today = Carbon::today();
        $type = $this->option('type');

        // For greetings, check holidays starting in next 2 days
        // For reminders, check holidays starting tomorrow specifically
        if ($type === 'greeting') {
            $holidays = Holiday::whereDate('start_date', '>=', $today)
                ->whereDate('start_date', '<=', $today->copy()->addDays(2))
                ->get();
            $this->info("Looking for upcoming holidays (today and next 2 days)");
        } else {
            // Only get holidays that start exactly tomorrow for reminders
            $tomorrow = $today->copy()->addDay();
            $holidays = Holiday::whereDate('start_date', $tomorrow)->get();
            $this->info("Looking for holidays starting tomorrow for reminders");
        }

        if ($holidays->isEmpty()) {
            $this->info('No applicable holidays found.');
            return;
        }

        $this->info("Found holidays:");
        foreach ($holidays as $holiday) {
            $this->info("Holiday: {$holiday->name}, Start: {$holiday->start_date}, End: {$holiday->end_date}");
        }

        // Get all active employees
        $employees = User::where('employee_status', 1)->get();

        if ($employees->isEmpty()) {
            $this->info('No active employees found.');
            return;
        }

        $this->info("Active employees found: " . $employees->count());

        // Testing email - make sure to change this to real emails in production
        $testingEmail = 'deepak.quantumitinnovation@gmail.com';

        // Set up mail subject and template based on type
        $subjectPrefix = ($type === 'reminder') ? 'Reminder: ' : '';
        $template = ($type === 'reminder') ? 'emails.festival_reminder' : 'emails.festival_greeting';

        // Set up batch processing and throttling to avoid SMTP limits
        $batchSize = 50; // Process in batches
        $delayBetweenBatches = 60; // seconds between batches
        $counter = 0;
        $batch = 0;

        foreach ($holidays as $holiday) {
            foreach ($employees as $employee) {
                try {
                    // Queue the email with explicit retry count and backoff
                    Mail::to($testingEmail)
                        ->later(now()->addSeconds($batch * $delayBetweenBatches), new FestivalGreeting(
                            $holiday->occasion,
                            $employee->name,
                            Carbon::parse($holiday->start_date)->format('M j, Y'),
                            Carbon::parse($holiday->end_date)->format('M j, Y'),
                            $type // Pass the type to the Mailable
                        ));

                    $counter++;

                    // Log success for debugging
                    $messageType = ($type === 'reminder') ? 'reminder' : 'greeting';
                    $this->info("Queued {$messageType} for {$employee->name} for {$holiday->occasion}");

                    // If we've reached the batch size, increment the batch counter
                    if ($counter % $batchSize === 0) {
                        $batch++;
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to queue email for {$employee->name}: " . $e->getMessage());
                    $this->error("Failed to queue email for {$employee->name}: " . $e->getMessage());
                }
            }
        }

        $this->info("All festival {$type}s queued successfully!");
    }
}
