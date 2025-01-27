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
            'organization_id' => Organization::first()->id,
            'name' => 'M Arslan',
            'email' => 'mughalarslan996@gmail.com',
            'phone' => '03177638978',
            'password' => Hash::make('12345678A'),
            'picture' => 'placeholder.jpg',
            'address' => [
                'address' => 'complete address',
                'street' => 'Test Street',
                'city' => 'Test City',
                'zip' => '12345',
                'coords' => [
                    'lat' => 31.5204,
                    'lng' => 74.3587
                ]
            ],
            'status' => Manager::STATUS_ACTIVE,
        ]);
    }
}
