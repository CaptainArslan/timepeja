<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VehicleType::insert([
            [
                'name' => 'toyota',
                'desc' => '12 seats',
                'created_at' => now()
            ],
            [
                'name' => 'hiace',
                'desc' => '15 seats',
                'created_at' => now()
            ],
            [
                'name' => 'coaster',
                'desc' => '34 seats',
                'created_at' => now()
            ],
        ]);
    }
}
