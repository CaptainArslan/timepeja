<?php

namespace Database\Seeders;

use Faker;
use Carbon\Carbon;
use App\Models\City;
use App\Models\State;
use App\Models\Manager;
use Illuminate\Support\Str;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use App\Models\OrganizationType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organization::factory(1)->create([
            'u_id' => 1,
            'name' => 'University Of Lahore',
            'branch_name' => 'Main Campus',
            'branch_code' => substr(uniqid(), -4),
            'o_type_id' =>  OrganizationType::inRandomOrder()->first()->id,
            'email' => 'mughalarslan996@gmail.com',
            'phone' => '03' . rand(0000000, 9999999),
            'code' => substr(uniqid(), -8),
            'address' => '',
            's_id' => State::inRandomOrder()->first()->id,
            'c_id' => City::inRandomOrder()->first()->id,
            'head_name' => 'Test 1',
            'head_email' => 'mughalarslan
            996@gmail.com',
            'head_phone' => '03' . rand(0000000, 9999999),
            'head_address' => 'Test Address',
            'status' => Organization::STATUS_ACTIVE,
        ]);
        // Organization::factory(1)->create([
        //     'u_id' => 1,
        //     'name' => 'University Of Lahore',
        //     'branch_name' => 'Main Campus',
        //     'branch_code' => substr(uniqid(), -4),
        //     'o_type_id' =>  OrganizationType::inRandomOrder()->first()->id,
        //     'email' => 'awabsabir373@gmail.com',
        //     'phone' => '03' . rand(0000000, 9999999),
        //     'code' => substr(uniqid(), -8),
        //     'address' => '',
        //     's_id' => State::inRandomOrder()->first()->id,
        //     'c_id' => City::inRandomOrder()->first()->id,
        //     'head_name' => 'Test 1',
        //     'head_email' => 'awabsabir373@gmail.com',
        //     'head_phone' => '03' . rand(0000000, 9999999),
        //     'head_address' => 'Test Address',
        //     'status' => Organization::STATUS_ACTIVE,
        // ]);
        // Organization::factory(5)->create();
    }
}
