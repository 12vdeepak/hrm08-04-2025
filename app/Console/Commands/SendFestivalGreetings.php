<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Holiday;
use App\Mail\FestivalGreetingMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendFestivalGreetings extends Command
{
    protected $signature = 'festival:greet';
    protected $description = 'Send festival greeting emails to active employees one day before festivals';

    public function handle()
    {
        $today = Carbon::today();
        $tomorrow = $today->copy()->addDay();

        // Get festivals happening tomorrow (one day before)
        $festivals = Holiday::whereDate('start_date', $tomorrow)->get();

        if ($festivals->isEmpty()) {
            $this->info('No festivals scheduled for tomorrow.');
            return;
        }

        // Get active employees
        $users = User::where('employee_status', 1)->get();

        if ($users->isEmpty()) {
            $this->info('No active employees found.');
            return;
        }

        $this->info('Sending festival greetings for ' . $festivals->count() . ' festival(s) occurring tomorrow to ' . $users->count() . ' employees.');

        $delaySeconds = 0;

        foreach ($festivals as $festival) {
            $this->info("Processing greetings for: {$festival->occasion} on {$festival->start_date}");
            
            foreach ($users as $user) {
                // Queue the email with incremental delay
                Mail::to('deepak.quantumitinnovation@gmail.com')
                    ->later(now()->addSeconds($delaySeconds), new FestivalGreetingMail($user, $festival));

                // Log the scheduled email
                Log::info("Scheduled {$festival->occasion} greeting to {$user->email} (one day before)");
                $this->line("→ Scheduled greeting to {$user->email}");

                $delaySeconds += 2; // Add delay between emails to avoid SMTP rate limits
            }
        }

        $this->info('All festival greetings have been queued with delay. Make sure the queue worker is running!');
    }
}