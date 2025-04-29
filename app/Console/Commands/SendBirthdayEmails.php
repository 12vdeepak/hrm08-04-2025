<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\BirthdayWishes;
use App\Models\User;

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
    protected $description = 'Send birthday emails to employees';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now()->format('m-d');
        
        // Query users who have birthdays today and are active employees
        $birthdayUsers = User::whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = ?", [$today])
            ->where('employee_status', 1)
            ->get();
            
        $this->info("Found {$birthdayUsers->count()} employees with birthdays today.");
            
        foreach ($birthdayUsers as $user) {
            // Send birthday email to each user
            Mail::to($user->email)->send(new BirthdayWishes($user));
            $this->info("Birthday email sent to {$user->name} ({$user->email})");
        }
        
        return Command::SUCCESS;
    }
}