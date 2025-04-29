<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\BirthdayWishesNotification;

class SendBirthdayEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send-birthday-wishes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send birthday wishes to employees who have their birthday today';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now();
        
        // Find users whose birthday is today (matching month and day)
        $birthdayUsers = User::whereMonth('date_of_birth', $today->month)
            ->whereDay('date_of_birth', $today->day)
            ->get();
            
        $count = 0;
        
        foreach ($birthdayUsers as $user) {
            $user->notify(new BirthdayWishesNotification());
            $count++;
            $this->info("Birthday email sent to: {$user->name} ({$user->email})");
        }
        
        $this->info("Total birthday emails sent: {$count}");
        
        return 0;
    }
}
