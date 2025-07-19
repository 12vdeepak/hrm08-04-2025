<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('activity:update')->everyMinute();
        $schedule->command('send:login-reminder')->weekdays()->at('10:31');
        $schedule->command('send:daily-status-report')->weekdays()->at('11:00');
        $schedule->command('send:late-mark-reminder')->weekdays()->at('20:00');
        $schedule->command('emails:send-birthday-wishes')->dailyAt('08:00');
        $schedule->command('festival:greet')
        ->dailyAt('09:00');
        $schedule->command('send:logout-reminder')->weekdays()->at('22:00'); // Reminder
        $schedule->command('send:logout-reminder')->weekdays()->at('23:00'); // Pending logout report
        $schedule->command('send:weekly-login-compliance-report')->fridays()->at('18:00');
    }




    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
