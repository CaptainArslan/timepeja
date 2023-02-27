<?php

namespace Database\Seeders;

use Nette\Utils\Random;
use Illuminate\Support\Str;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organization::insert([
            [
                'u_id' => 1,
                'name' => 'GC Faisalabad',
                'branch_name' => 'GC Faisalabad',
                'branch_code' => '123',
                'o_type' => 1,
                'email' => Str::random(5) . '@gmail.com',
                'phone' => '03001234567',
                'address' => Str::random(5),
                's_id' => 1,
                'c_id' => 1,
                'head_name' => 'Test 1',
                'head_email' => Str::random(5) . '@gmail.com',
                'head_phone' => random_int(1, 11),
                'status' => '1',
            ],
            [
                'u_id' => 1,
                'name' => 'GC gujranwala',
                'branch_name' => 'GC Gujranwala',
                'branch_code' => '456',
                'org_type' => 2,
                'email' => Str::random(5) . '@gmail.com',
                'phone' => '03176987745',
                'address' => Str::random(5),
                's_id' => 1,
                'c_id' => 1,
                'head_name' => 'Test 2',
                'head_email' => Str::random(5) . '@gmail.com',
                'head_phone' => random_int(1, 11),
                'status' => '1',
            ],
        ]);
    }
}
