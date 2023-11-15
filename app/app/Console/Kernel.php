<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * @example php artisan send:deadline-task-notifications
     * @example php artisan send:deadline-course-notifications
     * @example php artisan schedule:run
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        // Уведомления по дедлайнам тасок:
        $schedule->command('send:deadline-task-notifications')
            ->hourly()
            ->appendOutputTo('/app/storage/logs/laravel-scheduler.log');

        // Уведомления по дедлайнам курсов:
        $schedule->command('send:deadline-course-notifications')
            ->hourly()
            ->appendOutputTo('/app/storage/logs/laravel-scheduler.log');
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
