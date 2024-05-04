<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class DriverScheduleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info("Driver Schedule Notification Job Started");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
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
                $body = "Your upcoming schedule on date {$date} at {$scheduleTime} on route {$schedule->route->name} on vehicle number {$schedule->vehicle->number}";
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
