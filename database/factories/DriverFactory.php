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
            'o_id' => Organization::inRandomOrder()->first()->id,
            'u_id' => 1,
            'name' =>  $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            // 'password' => Hash::make('12345678'),
            'password' => '',
            'phone' => '03' . $this->faker->regexify('/^[0-9+]{2}-[0-9+]{7}$/'),
            'cnic' => $this->faker->regexify('/^[0-9+]{13}$/'),
            'cnic_front_pic' => $this->faker->imageUrl(),
            'cnic_back_pic' => $this->faker->imageUrl(),
            'cnic_expiry_date' => Carbon::now(),
            'license_no' => $this->faker->regexify('/^0{5}[1-9][0-9]{4}-[A-Z]{3}$/'),
            'license_no_front_pic' => $this->faker->imageUrl(),
            'license_no_back_pic' => $this->faker->imageUrl(),
            'license_expiry_date' => Carbon::now(),
            'otp' => '',
            // 'token' => Str::random(10),
            'status' => 1,
            'online_status' => $this->faker->randomElement([
                Driver::STATUS_ONLINE,
                Driver::STATUS_OFFLINE
            ]),
        ];
    }
}
