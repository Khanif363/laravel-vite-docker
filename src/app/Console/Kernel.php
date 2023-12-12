<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('ticket:auto-open')->everyMinute();
        $schedule->command('ticket:auto-open', [
            '--isEveryMoring' => true,
        ])->weekdays()->at('08:30');
        $schedule->command('email:notification', [
            '--isToGM' => true,
        ])->everyMinute();
        // $schedule->command('ticket:auto-pending')->weekdays()->at('17:00');
        $schedule->command('ticket:auto-pending')->fridays()->at('17:00');
        $schedule->command('email:notification', [
            '--isReminderClose' => true,
        ])->everyMinute();
        $schedule->command('email:notification', [
            '--isRating' => true,
        ])->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}