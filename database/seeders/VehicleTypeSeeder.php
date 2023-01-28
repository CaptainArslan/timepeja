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
            ],
            [
                'name' => 'hiace',
                'desc' => '15 seats',
            ],
            [
                'name' => 'coaster',
                'desc' => '34 seats',
            ],
        ]);
    }
}
