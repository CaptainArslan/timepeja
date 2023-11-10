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
                'desc' => '12 seats',
            ],
            [
                'name' => 'Bus Non-AC',
                'desc' => '15 seats',
            ],
            [
                'name' => 'Coaster AC',
                'desc' => '34 seats',
            ],
            [
                'name' => 'Coaster Non-AC',
                'desc' => '34 seats',
            ],
            [
                'name' => 'Toyota Hiace-AC',
                'desc' => '34 seats',
            ],
            [
                'name' => 'Toyota Hiace Non-AC',
                'desc' => '34 seats',
            ],
        ]);
    }
}
