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
                'name' => 'Bus AC',
                'description' => '12 seats',
            ],
            [
                'name' => 'Bus Non-AC',
                'description' => '15 seats',
            ],
            [
                'name' => 'Coaster AC',
                'description' => '34 seats',
            ],
            [
                'name' => 'Coaster Non-AC',
                'description' => '34 seats',
            ],
            [
                'name' => 'Toyota Hiace-AC',
                'description' => '34 seats',
            ],
            [
                'name' => 'Toyota Hiace Non-AC',
                'description' => '34 seats',
            ],
        ]);
    }
}
