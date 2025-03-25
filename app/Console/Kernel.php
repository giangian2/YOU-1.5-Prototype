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
        $schedule->command("create-detection",[1,6])->dailyAt('9:00');
        $schedule->command("create-detection",[1,2])->dailyAt('13:00');
        $schedule->command("create-detection",[1,5])->dailyAt('14:00');
        $schedule->command("create-detection",[1,3])->dailyAt('18:00');
        $schedule->command("create-detection",[1,4])->dailyAt('23:45');

        $schedule->command("create-daily-sensor-stats",[1])->dailyAt('23:50');

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
