<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Organization;
use App\Models\Passenger;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'organization_id' => Organization::inRandomOrder()->first()->id,
            'name' => $this->faker->name(),
            'passenger_id' => Passenger::inRandomOrder()->first()->id,
            'vehicle_id' => Vehicle::inRandomOrder()->first()->id,
            'driver_id' => Driver::inRandomOrder()->first()->id,
            'type' => $this->faker->randomElement(['vehicle','driver','passenger']),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ];
    }
}
