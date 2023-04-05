<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\VehicleType;
use Carbon\Carbon;
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
            'u_id' => 1,
            'v_type_id' => VehicleType::inRandomOrder()->first()->id,
            'number' => $this->faker->regexify('[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{4}'),
            'o_id' =>  Organization::inRandomOrder()->first()->id,
            'no_of_seat' => rand(0, 72),
            'front_pic' => $this->faker->imageUrl($width = 640, $height = 480, 'vehicle'),
            'back_pic' => $this->faker->imageUrl($width = 640, $height = 480, 'vehicle'),
            'number_pic' => $this->faker->imageUrl($width = 640, $height = 480, 'number plates'),
            'status' => 1,
            'created_at' => $this->faker->time(),
        ];
    }
}
