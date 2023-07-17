<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\RequestSeeder;
use Database\Seeders\VehicleTypeSeeder;
use Database\Seeders\CitySeederChunkOne;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\OrganizationTypeSeeder;
// use Database\Seeders\ModuleSeeder;
// use Database\Seeders\ModuleUrlSeeder;
// use Database\Seeders\PermissionSeeder;
// use Database\Seeders\ModuleGroupSeeder;


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
            // ModuleGroupSeeder::class,
            // ModuleSeeder::class,
            // ModuleUrlSeeder::class,
            // PermissionSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeederChunkOne::class,
            CitiesTableChunkTwoSeeder::class,
            CitiesTableChunkThreeSeeder::class,
            CitiesTableChunkFourSeeder::class,
            CitiesTableChunkFiveSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            OrganizationTypeSeeder::class,
            VehicleTypeSeeder::class,
            OrganizationSeeder::class,
            ManagerSeeder::class,
            RouteSeeder::class,
            VehicleSeeder::class,
            DriverSeeder::class,
            ScheduleSeeder::class,
            // TripSeeder::class
            PassengerSeeder::class,
        ]);
    }
}
