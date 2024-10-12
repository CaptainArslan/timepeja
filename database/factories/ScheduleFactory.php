<?php

namespace Database\Factories;

use App\Models\Driver;
use Carbon\Carbon;
use App\Models\Route;
use App\Models\Organization;
use App\Models\Schedule;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {

        $isDelayed = $this->faker->randomElement([
            Schedule::TRIP_ISDELAYED,
            Schedule::TRIP_NOTDELAYED
        ]);

        $delayReason = $isDelayed == Schedule::TRIP_ISDELAYED ? $this->faker->sentence() : null;

        // Start with the current time
        $currentDateTime = Carbon::now()->addMinutes(15);

        return [
            'u_id' => 1,
            'o_id' => Organization::inRandomOrder()->first()->id,
            'route_id' => Route::inRandomOrder()->first()->id,
            'v_id' => Vehicle::inRandomOrder()->first()->id,
            'd_id' => 1,
            'date' => $currentDateTime->toDateString(),
            'time' => $currentDateTime->toTimeString(),
            'status' => $this->faker->randomElement([
                Schedule::STATUS_DRAFT,
                Schedule::STATUS_PUBLISHED
            ]),
            'start_time' => $this->faker->time(),
            'end_time' => '',
            'trip_status' => $this->faker->randomElement([
                Schedule::TRIP_STATUS_UPCOMING,
                Schedule::TRIP_STATUS_INPROGRESS,
                Schedule::TRIP_STATUS_COMPLETED,

            ]),
            'is_delay' => $isDelayed,
            'delayed_reason' => $delayReason,
        ];
    }
}
