<?php

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Support\Str;
use App\Models\Organization;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
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
            'name' =>  $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'password' => Hash::make('12345678A'),
            'phone' => '03' . $this->faker->regexify('/^[0-9+]{2}-[0-9+]{7}$/'),
            'cnic' => $this->faker->regexify('/^[0-9+]{13}$/'),
            'cnic_front' => $this->faker->imageUrl(),
            'cnic_back' => $this->faker->imageUrl(),
            'license_no' => $this->faker->regexify('/^0{5}[1-9][0-9]{4}-[A-Z]{3}$/'),
            'license_front' => $this->faker->imageUrl(),
            'license_back' => $this->faker->imageUrl(),
            'status' => Driver::STATUS_ACTIVE,
            'online_status' => $this->faker->randomElement([
                Driver::ONLINE,
                Driver::OFFLINE
            ]),
        ];
    }
}
