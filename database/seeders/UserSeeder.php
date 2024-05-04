<?php

namespace Database\Seeders;

use App\Models\User;
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
            [
                'r_id' => 1,
                'full_name' => "Admin",
                'user_name' => "admin",
                'email' => "admin@admin.com",
                'phone' => "03171234567",
                'password' => Hash::make('12345678'),
                'designation' => "Admin",
                'image' => "placeholder.jpg",
                'status' => 0
            ],
            [
                'r_id' => 1,
                'full_name' => "Arslan",
                'user_name' => "arslan",
                'email' => "mughalarslan996@gmail.com",
                'phone' => "03177638978",
                'password' => Hash::make('12345678'),
                'designation' => "Admin",
                'image' => "placeholder.jpg",
                'status' => 0
            ]
        ]);
    }
}
