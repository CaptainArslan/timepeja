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
            'organization_id' => Organization::inRandomOrder()->first()->id,
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => '03' . $this->faker->regexify('/^[0-9+]{9}$/'),
            'password' => Hash::make('12345678A'),
            'picture' => 'placeholder.jpg',
            'address' => [
                'address' => $this->faker->address(),
                'street' => $this->faker->streetName(),
                'city' => $this->faker->city(),
                'zip' => $this->faker->postcode(),
                'coords' => [
                    'lat' => $this->faker->latitude(),
                    'lng' => $this->faker->longitude()
                ]
            ],
            'status' => 1,
        ];
    }
}
