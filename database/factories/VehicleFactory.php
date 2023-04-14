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
            'front_pic' => 'placeholder.jpg',
            'back_pic' => 'placeholder.jpg',
            'number_pic' => 'placeholder.jpg',
            'status' => 1,
            'created_at' => $this->faker->time(),
        ];
    }
}
