<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\State;
use Illuminate\Support\Str;
use App\Models\OrganizationType;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'branch_name' => $this->faker->city(),
            'branch_code' => substr(uniqid(), -4),
            'organization_type_id' =>  OrganizationType::inRandomOrder()->first()->id,
            'email' => $this->faker->safeEmail(),
            'phone' => '03' . $this->faker->regexify('/^[0-9+]{2}-[0-9+]{7}$/'),
            'code' => substr(uniqid(), -8),
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
            'state_id' => State::inRandomOrder()->first()->id,
            'city_id' => City::inRandomOrder()->first()->id,
            'head_name' => $this->faker->name(),
            'head_email' => $this->faker->safeEmail(),
            'head_phone' => '03' . $this->faker->regexify('/^[0-9+]{2}-[0-9+]{7}$/'),
            'head_address' => json_encode([
                'address' => $this->faker->address(),
                'street' => $this->faker->streetName(),
                'city' => $this->faker->city(),
                'zip' => $this->faker->postcode(),
                'coords' => [
                    'lat' => $this->faker->latitude(),
                    'lng' => $this->faker->longitude()
                ]
            ]),
            'status' => 1,
        ];
    }
}
