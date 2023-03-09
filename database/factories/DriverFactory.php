<?php

namespace Database\Factories;

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
            'password' => Hash::make('12345678'),
            'phone' => $this->faker->phoneNumber(),
            'cnic' => $this->faker->regexify('/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/'),
            'cnic_front_pic' => $this->faker->imageUrl($width = 640, $height = 480, 'number plates'),
            'cnic_back_pic' => $this->faker->imageUrl($width = 640, $height = 480, 'number plates'),
            'cnic_expiry_date' => Carbon::now(),
            'license_no' => $this->faker->regexify('/^0{5}[1-9][0-9]{4}-[A-Z]{3}$/'),
            'license_no_front_pic' => $this->faker->imageUrl($width = 640, $height = 480, 'number plates'),
            'license_no_back_pic' => $this->faker->imageUrl($width = 640, $height = 480, 'number plates'),
            'license_expiry_date' => Carbon::now(),
            'otp' => substr(uniqid(), -4),
            'token' => Str::random(10),
            'status' => 1,
            'online_status' => 1,
        ];
    }
}
