<?php

namespace Database\Seeders;

use App\Models\OrganizationType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrganizationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrganizationType::insert([
            [
                'name' => 'primary',
                'desc' => 'Play to 5th',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Middle School',
                'desc' => '6th to 8th ',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Higer School',
                'desc' => '9th to 10th ',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'College',
                'desc' => '11th to 12th ',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Higher College',
                'desc' => 'Bachlor University',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'University',
                'desc' => 'Master Level / PHD',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Other',
                'desc' => 'Private and other organization',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
