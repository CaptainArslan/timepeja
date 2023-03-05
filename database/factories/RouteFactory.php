<?php

namespace Database\Factories;

use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class RouteFactory extends Factory
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
            'name' => $this->faker->name,
            'number' => rand(1000, 9999),
            'from' => $this->faker->city,
            'from_longitude' => $this->faker->longitude,
            'from_latitude' => $this->faker->latitude,
            'to' => $this->faker->city(),
            'to_longitude' => $this->faker->longitude,
            'to_latitude' => $this->faker->latitude,
            'status' => 1,
            'created_at' => Carbon::now(),
        ];
    }
}
