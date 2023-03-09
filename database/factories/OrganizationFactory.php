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
            'u_id' => 1,
            'name' => $this->faker->name(),
            'branch_name' => $this->faker->city(),
            'branch_code' => substr(uniqid(), -4),
            'o_type_id' =>  OrganizationType::inRandomOrder()->first()->id,
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => Str::random(5),
            's_id' => State::inRandomOrder()->first()->id,
            'c_id' => City::inRandomOrder()->first()->id,
            'head_name' => 'Test 1',
            'head_email' => $this->faker->safeEmail(),
            'head_phone' => $this->faker->phoneNumber(),
            'status' => 1,
        ];
    }
}
