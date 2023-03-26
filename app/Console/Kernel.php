<?php

namespace App\Console;

use App\Jobs\AdjustHolidayAllowanceJob;
use App\Jobs\PruneSentEmailsJob;
use App\Jobs\SendNotificationSummaryJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('cache:prune-stale-tags')->hourly();
        $schedule->command('model:prune')->daily();
        $schedule->job(new PruneSentEmailsJob)->daily();
        $schedule->job(new AdjustHolidayAllowanceJob)->daily();
        $schedule->job(new SendNotificationSummaryJob())->dailyAt('07:00');
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
