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
        return [
            'o_id' => Organization::inRandomOrder()->first()->id,
            'u_id' => 1,
            'route_id' =>  Route::inRandomOrder()->first()->id,
            'v_id' => Vehicle::inRandomOrder()->first()->id,
            'd_id' => Driver::inRandomOrder()->first()->id,
            'date' => Carbon::now(),
            'time' => $this->faker->time(),
            'status' => Schedule::STATUS_DRAFT
        ];
    }
}
