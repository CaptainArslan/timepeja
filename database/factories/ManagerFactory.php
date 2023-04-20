<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManagerFactory extends Factory
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
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => '03174407032',
            // 'phone' => '03' . $this->faker->regexify('/^[0-9+]{2}-[0-9+]{7}$/'),
            'password' => Hash::make('12345678A'),
            'picture' => 'placeholder.jpg',
            'otp' => rand(1000, 9999),
            'address' => $this->faker->address(),
            'status' => 1,
        ];
    }
}
