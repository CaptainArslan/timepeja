<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Requests;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'request_id' => Requests::inRandomOrder()->first()->id,
            'name' => $this->faker->name(),
            'phone' => '03' . $this->faker->regexify('/^[0-9+]{9}$/'),
            'email' => $this->faker->safeEmail(),
            'image' => '',
            'house_no' => rand(1, 10000),
            'street_no' => rand(1, 10000),
            'town' => $this->faker->city(),
            'additional_detail' => $this->faker->address(),
            'city_id' => City::inRandomOrder()->first()->id,
            'pickup_address' => $this->faker->address(),
            'pickup_city_id' => $this->faker->address(),
            'lattitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'status' => $this->faker->randomElement([
                true, false
            ]),
            'created_at' => Carbon::now()->subDays(14),
            'updated_at' => Carbon::now()->subDays(14),
        ];
    }
}
