<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vehicle_type_id' => VehicleType::inRandomOrder()->first()->id,
            'number' => $this->faker->regexify('[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{4}'),
            'organization_id' =>  Organization::inRandomOrder()->first()->id,
            'no_of_seat' => rand(0, 72),
            'front_pic' => $this->faker->imageUrl(),
            'back_pic' => $this->faker->imageUrl(),
            'number_pic' => $this->faker->imageUrl(),
            'created_at' => $this->faker->time(),
            'status' => Vehicle::STATUS_ACTIVE,
        ];
    }
}
