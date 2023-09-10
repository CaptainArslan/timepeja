<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Driver::factory(1)->create([
            'o_id' => 1,
            'u_id' => 1,
            'name' =>  'Driver',
            'email' => 'mughalarslan996@gmail.com',
            'password' => '',
            'phone' => '03177638978',
            'cnic' => '34101' . rand(00000000, 99999999),
            'cnic_front_pic' => null,
            'cnic_back_pic' => null,
            'cnic_expiry_date' => Carbon::now(),
            'license_no' => '0512345ABC',
            'license_no_front_pic' => null,
            'license_no_back_pic' => null,
            'license_expiry_date' => Carbon::now(),
            'otp' => 1234,
            'status' => 1,
            'online_status' => Driver::STATUS_ONLINE,
        ]);
        Driver::factory(10)->create();
    }
}
