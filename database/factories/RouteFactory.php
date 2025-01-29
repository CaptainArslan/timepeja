<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Route;
use Illuminate\Database\Eloquent\Factories\Factory;

class RouteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $city1 = $this->faker->city();
        $city2 = $this->faker->city();
        $number = rand(1000, 9999);
        $name = $number . '-' . $city1 . ' To ' . $city2;
        return [
            'organization_id' => Organization::inRandomOrder()->first()->id,
            'name' => $name,
            'number' => $number,
            'from' => [
                'adderss' => $this->faker->address(),
                'state' => $this->faker->state(),
                'city' => $city1,
                'coordinates' => [
                    'latitude' => $this->faker->latitude(),
                    'longitude' => $this->faker->longitude(),
                ],
            ],
            'to' => [
                'adderss' => $this->faker->address(),
                'state' => $this->faker->state(),
                'city' => $city2,
                'coordinates' => [
                    'latitude' => $this->faker->latitude(),
                    'longitude' => $this->faker->longitude(),
                ],
            ],
            'status' => Route::STATUS_ACTIVE,
            'way_points' => [
                [
                    'latitude' => 32.194276,
                    'longitude' => 74.201953,
                ],
                [
                    'latitude' => 32.194276,
                    'longitude' => 74.203517,
                ],
            ],
        ];
    }
}
