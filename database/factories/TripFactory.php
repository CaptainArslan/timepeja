<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Schedule;
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
            'o_id' => Organization::inRandomOrder()->first()->id,
            'u_id'=> 1,
            'sch_id'=> Schedule::inRandomOrder()->first()->id,
            'delay_reason'=> $this->faker->sentence(),
            'delay_time'=> $this->faker->time(),
            'status'=> $this->faker->randomElement(['pending', 'in-progress', 'completed', 'delay']),
            'created_at' => $this->faker->date()
        ];
    }
}
