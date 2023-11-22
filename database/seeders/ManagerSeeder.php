<?php

namespace Database\Seeders;

use App\Models\Manager;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Manager::factory(1)->create([
            'o_id' => 1,
            'u_id' => 1,
            'name' => 'Test 1',
            'email' => 'mughalarslan996@gmail.com',
            'phone' => '03177638978',
            // 'phone' => '03' . $this->faker->regexify('/^[0-9+]{2}-[0-9+]{7}$/'),
            'password' => Hash::make('12345678A'),
            'picture' => 'placeholder.jpg',
            'otp' => rand(1000, 9999),
            'address' => 'Test Address ',
            'status' => Manager::STATUS_ACTIVE,
        ]);
        Manager::factory(1)->create([
            'o_id' => 2,
            'u_id' => 1,
            'name' => 'Test 1',
            'email' => 'awabsabir373@gmail.com',
            'phone' => '03174407032',
            // 'phone' => '03' . $this->faker->regexify('/^[0-9+]{2}-[0-9+]{7}$/'),
            'password' => Hash::make('12345678A'),
            'picture' => 'placeholder.jpg',
            'otp' => rand(1000, 9999),
            'address' => 'Test Address ',
            'status' => Manager::STATUS_ACTIVE,
        ]);
        Manager::factory(5)->create();
    }
}
