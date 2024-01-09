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
        Driver::factory(1)->create([
            'o_id' => 1,
            'u_id' => 1,
            'name' =>  'Muhammad Arslan',
            'email' => 'mughalarslan996@gmail.com',
            'password' => Hash::make('12345678A'),
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
            'device_token' => 'fLE6PePxTFmDxCzeFeM_H_:APA91bGJWQC2gBwv-wQT8iE3Esn0cRkm6cnLg5WaNj2FJSyTEso6TUuo-5pfcE0EDEqTD1LX_MFBpf7kNxHO-FhgUFHxuGMHk-cZKi1zknP9doMo7GZso89VLBVGfP1Veh80WgFvs-hy'
        ]);
        // Driver::factory(5)->create();
    }
}
