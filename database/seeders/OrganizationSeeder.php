<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Manager;
use Illuminate\Support\Str;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use App\Models\OrganizationType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organization::factory(1)->create();
        // DB::table('organizations')->delete();
        // Organization::insert([
        //     [
        //         'id' => 1,
        //         'u_id' => 1,
        //         'name' => 'Gujrant University',
        //         'branch_name' => 'Faisalabad',
        //         'branch_code' => '123',
        //         'o_type_id' => 1,
        //         'email' => Str::random(5) . '@gmail.com',
        //         'phone' => '03001234567',
        //         'address' => Str::random(5),
        //         's_id' => 1,
        //         'c_id' => 1,
        //         'head_name' => 'Test 1',
        //         'head_email' => Str::random(5) . '@gmail.com',
        //         'head_phone' => random_int(1, 11),
        //         'status' => '1',
        //         'created_at' => Carbon::now()
        //     ],
        //     [
        //         'id' => 2,
        //         'u_id' => 1,
        //         'name' => 'Punjab University',
        //         'branch_name' => 'Gujranwala',
        //         'branch_code' => '456',
        //         'o_type_id' => 2,
        //         'email' => Str::random(5) . '@gmail.com',
        //         'phone' => '03176987745',
        //         'address' => Str::random(5),
        //         's_id' => 1,
        //         'c_id' => 1,
        //         'head_name' => 'Test 2',
        //         'head_email' => Str::random(5) . '@gmail.com',
        //         'head_phone' => random_int(1, 11),
        //         'status' => '1',
        //         'created_at' => Carbon::now()
        //     ],
        // ]);
    }
}
