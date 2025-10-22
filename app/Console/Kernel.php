<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define your scheduled commands.
     */
    protected function schedule(Schedule $schedule)
    {
        // ✅ Automatically promote every June 1st at midnight
        $schedule->command('students:promote')->yearlyOn(6, 1, '00:00');

        // You can test more frequently while developing:
        // $schedule->command('students:promote')->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
