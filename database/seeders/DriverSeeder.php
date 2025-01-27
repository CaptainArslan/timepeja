<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Driver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Driver::create([
            'organization_id' => 1,
            'name' =>  'Muhammad Arslan',
            'email' => 'mughalarslan996@gmail.com',
            'password' => Hash::make('12345678A'),
            'phone' => '03177638978',
            'cnic' => '34101' . rand(00000000, 99999999),
            'cnic_front' => null,
            'cnic_back' => null,
            'license_no' => '0512345ABC',
            'license_front' => null,
            'license_back' => null,
            'status' => Driver::STATUS_ACTIVE,
            'online_status' => Driver::ONLINE,
        ]);

        Driver::factory(100)->create();
    }
}
