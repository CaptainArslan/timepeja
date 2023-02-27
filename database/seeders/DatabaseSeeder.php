<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\ModuleSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\ModuleUrlSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\ModuleGroupSeeder;
use Database\Seeders\VehicleTypeSeeder;
use Database\Seeders\CitySeederChunkOne;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\OrganizationTypeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ModuleGroupSeeder::class,
            ModuleSeeder::class,
            ModuleUrlSeeder::class,
            PermissionSeeder::class,
            OrganizationTypeSeeder::class,
            OrganizationSeeder::class,
            VehicleTypeSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeederChunkOne::class,
            
        ]);
    }
}
