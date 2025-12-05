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
        // $schedule->command('inspire')->hourly();
        
        // Check subscription expirations daily at 9:00 AM
        $schedule->command('subscription:check-expiration')
                 ->dailyAt('09:00')
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Send daily tender notifications at 9:00 AM
        $schedule->command('tenders:send-notifications daily')
                 ->dailyAt('09:00')
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Send weekly tender notifications every Monday at 9:00 AM
        $schedule->command('tenders:send-notifications weekly')
                 ->weeklyOn(1, '09:00')
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Send monthly tender notifications on the 1st of each month at 9:00 AM
        $schedule->command('tenders:send-notifications monthly')
                 ->monthlyOn(1, '09:00')
                 ->withoutOverlapping()
                 ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

