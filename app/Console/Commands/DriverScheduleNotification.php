<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DriverScheduleNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'driver:schedule-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command to send notification to driver for next 15 upcoming schedule';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    // public function handle()
    // {
    //     Log::info("Driver Schedule Notification Command Started");
    //     $currentTime = now();
    //     $endTime = now()->addMinutes(15);

    //     $driverSchedules = Schedule::where('status', Schedule::STATUS_PUBLISHED)
    //         ->where('date', $currentTime->toDateString())
    //         ->where('time', '>=', $currentTime->toTimeString())
    //         ->where('time', '<=', $endTime->toTimeString())
    //         ->isNotNotified()
    //         ->get();

    //     foreach ($driverSchedules as $schedule) {
    //         $driver = $schedule->driver;
    //         // Log::info($driver->device_token);
    //         $scheduleTime = Carbon::parse($schedule->time)->format('g:i A');
    //         $date = Carbon::parse($schedule->date)->format('d/m/Y');
    //         if ($driver->device_token != null) {
    //             $body = "Upcoming schedule for {$driver->name}: {$date} at {$scheduleTime} on route {$schedule->route->name} on vehicle number {$schedule->vehicle->name}";
    //             Log::info($body);
    //             $title = "Upcoming Schedule";
    //             notification($title, $body, $schedule, $driver->device_token);
    //         }
    //         $schedule->is_notified = true;
    //         $schedule->save();
    //     }
    //     Log::info("Driver Schedule Notification Command Ended");
    //     return 0;
    // }
    public function handle()
    {
        Log::info("Driver Schedule Notification Command Started");

        $currentTime = now();
        $endTime = now()->addMinutes(15);

        $driverSchedules = Schedule::where('status', Schedule::STATUS_PUBLISHED)
            ->where('date', $currentTime->toDateString())
            ->where('time', '>=', $currentTime->toTimeString())
            ->where('time', '<=', $endTime->toTimeString())
            ->where('is_notified', 0)
            // ->with(['driver', 'route', 'vehicle']) // Eager load relationships
            ->get();

        foreach ($driverSchedules as $schedule) {
            $driver = $schedule->driver;
            Log::info($driver->device_token);
            if ($driver->device_token) {
                $scheduleTime = Carbon::parse($schedule->time)->format('g:i A');
                $date = Carbon::parse($schedule->date)->format('d/m/Y');
                $title = "Upcoming Schedule";
                $body = "Your upcoming schedule on date {$date} at {$scheduleTime} on route {$schedule->route->name} on vehicle number {$schedule->vehicle->name}";
                Log::info($body);
                notification($title, $body, $schedule, $driver->device_token);
            }
            $schedule->is_notified = true;
            $schedule->save();
        }

        Log::info("Driver Schedule Notification Command Ended");
        return 0;
    }
}
