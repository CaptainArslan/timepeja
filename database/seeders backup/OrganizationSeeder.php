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
                'user_id' => 1,
                'name' => 'GC Faisalabad',
                'branch_name' => 'GC Faisalabad',
                'branch_code' => 'fsd',
                'org_type' => 1,
                'email'=> Str::random(5).'@gmail.com',
                'phone'=> '03001234567',
                'address'=> Str::random(5),
                'state'=> 1,
                'city'=> 1,
                'head_name'=> 'Romi'
            ],
            [
                'user_id' => 1,
                'name' => 'GC gujranwala',
                'branch_name' => 'GC Gujranwala',
                'branch_code' => 'grw',
                'org_type' => 2,
                'email'=> Str::random(5).'@gmail.com',
                'phone'=> '03176987745',
                'address'=> Str::random(5),
                'state'=> 1,
                'city'=> 1,
                'head_name'=> 'Shami'
            ],
        ]);
    }
}
