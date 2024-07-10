<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Schedule;
use App\Models\Organization;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class FifteenScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schedule::insert(
            [
                'u_id' => 1,
                'o_id' => Organization::inRandomOrder()->first()->id,
                'route_id' =>  Route::inRandomOrder()->first()->id,
                'v_id' => Vehicle::inRandomOrder()->first()->id,
                'd_id' => 1,
                'date' => Carbon::now()->format('Y-m-d'),
                'time' => now()->addMinutes(10)->format('H:i:s'),
                'status' => Schedule::STATUS_PUBLISHED,
                'start_time' => '',
                'end_time' => '',
                'trip_status' => Schedule::TRIP_STATUS_UPCOMING,
                'is_delay' => false,
                'delayed_reason' => null,
            ],
            [
                'u_id' => 1,
                'o_id' => Organization::inRandomOrder()->first()->id,
                'route_id' =>  Route::inRandomOrder()->first()->id,
                'v_id' => Vehicle::inRandomOrder()->first()->id,
                'd_id' => 1,
                'date' => Carbon::now()->format('Y-m-d'),
                'time' => now()->addMinutes(10)->format('H:i:s'),
                'status' => Schedule::STATUS_PUBLISHED,
                'start_time' => '',
                'end_time' => '',
                'trip_status' => Schedule::TRIP_STATUS_UPCOMING,
                'is_delay' => false,
                'delayed_reason' => null,
            ],
            [
                'u_id' => 1,
                'o_id' => Organization::inRandomOrder()->first()->id,
                'route_id' =>  Route::inRandomOrder()->first()->id,
                'v_id' => Vehicle::inRandomOrder()->first()->id,
                'd_id' => 1,
                'date' => Carbon::now()->format('Y-m-d'),
                'time' => now()->addMinutes(10)->format('H:i:s'),
                'status' => Schedule::STATUS_PUBLISHED,
                'start_time' => '',
                'end_time' => '',
                'trip_status' => Schedule::TRIP_STATUS_UPCOMING,
                'is_delay' => false,
                'delayed_reason' => null,
            ],
            [
                'u_id' => 1,
                'o_id' => Organization::inRandomOrder()->first()->id,
                'route_id' =>  Route::inRandomOrder()->first()->id,
                'v_id' => Vehicle::inRandomOrder()->first()->id,
                'd_id' => 1,
                'date' => Carbon::now()->format('Y-m-d'),
                'time' => now()->addMinutes(10)->format('H:i:s'),
                'status' => Schedule::STATUS_PUBLISHED,
                'start_time' => '',
                'end_time' => '',
                'trip_status' => Schedule::TRIP_STATUS_UPCOMING,
                'is_delay' => false,
                'delayed_reason' => null,
            ],
        );
    }
}
