<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Route;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'o_id'=> Organization::inRandomOrder()->first()->id,
            'u_id'=> 1,
            'route_id'=>  Route::inRandomOrder()->first()->id,
            'v_id'=> Route::inRandomOrder()->first()->id,
            'd_id'=> Route::inRandomOrder()->first()->id,
            'date'=> Carbon::now(),
            'time'=> time(),
            'status' => 1
        ];
    }
}
