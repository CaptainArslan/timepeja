<?php

namespace Database\Factories;

use App\Models\Passenger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class PassengerFactory extends Factory
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
            'phone' => '03' . $this->faker->regexify('/^[0-9+]{2}-[0-9+]{7}$/'),
            'email' => $this->faker->safeEmail(),
            'password' => Hash::make('12345678'),
            'unique_id' => substr(uniqid(), -8),
            'gaurd_code' => substr(uniqid(), -8),
            'bio' => $this->faker->sentence(),
            'location' => $this->faker->longitude,
            'lattutude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'google' => null,
            'google_id' => null,
            'facebook' => null,
            'facebook_id' => null,
            'twitter' => null,
            'twitter_id' => null,
            'image' => null,
            'otp' => null,
            'status' => Passenger::STATUS_ACTIVE,
            'remember_token' => null,
            'created_at' => Carbon::now()->subDays(14),
            'updated_at' => Carbon::now()->subDays(14),
        ];
    }
}
