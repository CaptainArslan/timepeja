<?php

namespace Database\Seeders;

use App\Models\Setting\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'r_id' => 1,
            'full_name' => "Azam Naveed",
            'user_name' => "azam",
            'email' => "admin@admin.com",
            'password' => Hash::make('12345678'),
            'designation' => "Admin",
            'status' => 0
        ]);
    }
}
