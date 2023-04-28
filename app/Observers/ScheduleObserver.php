<?php

namespace App\Observers;

use App\Models\Schedule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ScheduleObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the Schedule "created" event.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return void
     */
    public function created(Schedule $schedule)
    {
        if (Cache::has('ORGANIZATION_ROUTE_VEHICLE_DRIVER_SCHEDULE_DATA_')) {
            Cache::forget('ORGANIZATION_ROUTE_VEHICLE_DRIVER_SCHEDULE_DATA_' . $schedule->o_id);
        }
        if (Cache::has('PUBLISHED_SCHEDULE_')) {
            Cache::forget('PUBLISHED_SCHEDULE_' . $schedule->o_id);
        }
        if (Cache::has('CREATED_SCHEDULE_')) {
            Cache::forget('CREATED_SCHEDULE_' . $schedule->o_id);
        }
        if (Cache::has('SCREEN_WRAPPER_')) {
            Cache::forget('SCREEN_WRAPPER_' . $schedule->o_id);
        }
        Log::info('Schedule id ' . $schedule->id . ' for date ' . $schedule->date . ' is created at ' . date('Y-m-d'));
    }

    /**
     * Handle the Schedule "updated" event.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return void
     */
    public function updated(Schedule $schedule)
    {
        if (Cache::has('ORGANIZATION_ROUTE_VEHICLE_DRIVER_SCHEDULE_DATA_')) {
            Cache::forget('ORGANIZATION_ROUTE_VEHICLE_DRIVER_SCHEDULE_DATA_' . $schedule->o_id);
        }
        if (Cache::has('PUBLISHED_SCHEDULE_')) {
            Cache::forget('PUBLISHED_SCHEDULE_' . $schedule->o_id);
        }
        if (Cache::has('CREATED_SCHEDULE_')) {
            Cache::forget('CREATED_SCHEDULE_' . $schedule->o_id);
        }
        if (Cache::has('SCREEN_WRAPPER_')) {
            Cache::forget('SCREEN_WRAPPER_' . $schedule->o_id);
        }
        Log::info('Schedule id ' . $schedule->id . ' for date ' . $schedule->date . ' is updated at ' . date('Y-m-d'));
    }

    /**
     * Handle the Schedule "deleted" event.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return void
     */
    public function deleted(Schedule $schedule)
    {
        if (Cache::has('ORGANIZATION_ROUTE_VEHICLE_DRIVER_SCHEDULE_DATA_')) {
            Cache::forget('ORGANIZATION_ROUTE_VEHICLE_DRIVER_SCHEDULE_DATA_' . $schedule->o_id);
        }
        if (Cache::has('PUBLISHED_SCHEDULE_')) {
            Cache::forget('PUBLISHED_SCHEDULE_' . $schedule->o_id);
        }
        if (Cache::has('CREATED_SCHEDULE_')) {
            Cache::forget('CREATED_SCHEDULE_' . $schedule->o_id);
        }
        if (Cache::has('SCREEN_WRAPPER_')) {
            Cache::forget('SCREEN_WRAPPER_' . $schedule->o_id);
        }
        Log::info('Schedule id ' . $schedule->id . ' for date ' . $schedule->date . ' is deleted at ' . date('Y-m-d'));
    }

    /**
     * Handle the Schedule "restored" event.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return void
     */
    public function restored(Schedule $schedule)
    {
        if (Cache::has('ORGANIZATION_ROUTE_VEHICLE_DRIVER_SCHEDULE_DATA_')) {
            Cache::forget('ORGANIZATION_ROUTE_VEHICLE_DRIVER_SCHEDULE_DATA_' . $schedule->o_id);
        }
        if (Cache::has('PUBLISHED_SCHEDULE_')) {
            Cache::forget('PUBLISHED_SCHEDULE_' . $schedule->o_id);
        }
        if (Cache::has('CREATED_SCHEDULE_')) {
            Cache::forget('CREATED_SCHEDULE_' . $schedule->o_id);
        }
        if (Cache::has('SCREEN_WRAPPER_')) {
            Cache::forget('SCREEN_WRAPPER_' . $schedule->o_id);
        }
        Log::info('Schedule id ' . $schedule->id . ' for date ' . $schedule->date . ' is restored at ' . date('Y-m-d'));
    }

    /**
     * Handle the Schedule "force deleted" event.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return void
     */
    public function forceDeleted(Schedule $schedule)
    {
        if (Cache::has('ORGANIZATION_ROUTE_VEHICLE_DRIVER_SCHEDULE_DATA_')) {
            Cache::forget('ORGANIZATION_ROUTE_VEHICLE_DRIVER_SCHEDULE_DATA_' . $schedule->o_id);
        }
        if (Cache::has('PUBLISHED_SCHEDULE_')) {
            Cache::forget('PUBLISHED_SCHEDULE_' . $schedule->o_id);
        }
        if (Cache::has('CREATED_SCHEDULE_')) {
            Cache::forget('CREATED_SCHEDULE_' . $schedule->o_id);
        }
        if (Cache::has('SCREEN_WRAPPER_')) {
            Cache::forget('SCREEN_WRAPPER_' . $schedule->o_id);
        }
        Log::info('Schedule id ' . $schedule->id . ' for date ' . $schedule->date . ' is forcefuly deleted at ' . date('Y-m-d'));
    }
}
