<?php

namespace App\Console;

use Commands\ScheduleSeed;
use Commands\MigrateInOrder;
use App\Console\Commands\MakeModelSetup;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // MigrateInOrder::class,
        // ScheduleSeed::class,
        MakeModelSetup::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('schedule:seed')->daily();
        // $schedule->command('inspire')->hourly();
        $schedule->command('driver:schedule-notification')->everyMinute();
        
        $schedule->command('queue:work --stop-when-empty')
            ->everyMinute()
            ->withoutOverlapping();
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
