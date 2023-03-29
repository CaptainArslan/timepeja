<?php

namespace Database\Factories;

use App\Models\Trip;
use App\Models\Schedule;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'u_id' => 1,
            'sch_id' => Schedule::inRandomOrder()->first()->id,
            'actual_start_time' => $this->faker->time(),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'delayed' => $this->faker->randomElement([
                Trip::IS_DELAYED,
                Trip::IS_NOT_DELAYED
            ]),
            'delay_reason' => $this->faker->sentence(),
            'status' => $this->faker->randomElement([
                Trip::STATUS_PENDING,
                Trip::STATUS_INPROGRESS,
                Trip::STATUS_COMPLETED,
                Trip::STATUS_DELAY
            ]),
            'created_at' => $this->faker->date()
        ];
    }
}
