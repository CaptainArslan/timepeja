<?php

namespace Database\Seeders;

use App\Models\Requests;
use Illuminate\Database\Seeder;

class RequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Requests::factory(100)->create();
    }
}
