<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Passenger;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PassengerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Passenger::factory(1)->create([
            'name' => 'Arslan Mughala',
            'phone' => '03177638978',
            'email' => 'mughalarslan996@gmail.com',
            'password' => Hash::make('12345678'),
            'otp' => rand(100000, 999999),
            'unique_id' => substr(uniqid(), -3),
            // 'guardian_code' => substr(uniqid(), -8),
            'bio' => '',
            // 'location' => $this->faker->longitude,
            // 'lattutude' => $this->faker->latitude,
            // 'longitude' => $this->faker->longitude,
            // 'google' => null,
            // 'google_id' => null,
            // 'facebook' => null,
            // 'facebook_id' => null,
            // 'twitter' => null,
            // 'twitter_id' => null,
            'image' => null,
            'otp' => null,
            'status' => Passenger::STATUS_ACTIVE,
            'remember_token' => null,
            'created_at' => Carbon::now()->subDays(14),
            'updated_at' => Carbon::now()->subDays(14),
        ]);
        // Passenger::factory(10)->create();
    }
}
