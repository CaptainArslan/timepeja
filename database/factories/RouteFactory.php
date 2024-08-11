<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Route;
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
        $from = $this->faker->city();
        $to = $this->faker->city();
        $number = rand(1000, 9999);
        $name = $number . '-' . $from . ' To ' . $to;
        return [
            'o_id' => Organization::inRandomOrder()->first()->id,
            'u_id' => 1,
            'name' => $name,
            'number' => $number,
            'from' => $from,
            'from_latitude' => 32.195526,
            'from_longitude' => 74.2020717,
            'to' => $to,
            'to_latitude' => 32.195526,
            'to_longitude' => 74.2020717,
            'status' => Route::STATUS_ACTIVE,
            'created_at' => time(),
        ];
    }
}
