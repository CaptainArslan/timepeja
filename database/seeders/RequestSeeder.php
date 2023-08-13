<?php

namespace Database\Seeders;

use App\Models\Request as Requests;
use Illuminate\Database\Seeder;

class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Requests::factory()->count(25)->create();
    }
}
