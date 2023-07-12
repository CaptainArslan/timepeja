<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Student;
use App\Models\Requests;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuardianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $emp_id = null;
        $student_id = $this->faker->randomElement([
            Student::inRandomOrder()->first()->id, null
        ]);

        if (!$student_id) {
            $emp_id =  Student::inRandomOrder()->first()->id;
        }
        return [
            'request_id' => Requests::inRandomOrder()->first()->id,
            'student_id' => $student_id,
            'employee_id' => $emp_id,
            'name' => $this->faker->name(),
            'phone' => '03' . $this->faker->regexify('/^[0-9+]{9}$/'),
            'email' => $this->faker->safeEmail(),
            'image' => '',
            'house_no' => rand(1, 100000),
            'street_no' => rand(1, 100000),
            'town' => $this->faker->city(),
            'additional_detail' => $this->faker->address(),
            'city_id' => City::inRandomOrder()->first()->id,
            'cnic' => $this->faker->regexify('/^[0-9+]{13}$/'),
            'cnic_front' => '',
            'cnic_back' => '',
            'guarian_code' => substr(uniqid(), -8),
            'pickup_address' => $this->faker->address(),
            'pickup_city_id' => City::inRandomOrder()->first()->id,
            'lattitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'relation' => $this->faker->randomElements(['mother', 'father', 'brother', 'sister']),
            'status' => $this->faker->randomElement([
                true, false
            ]),
            'created_at' => Carbon::now()->subDays(14),
            'updated_at' => Carbon::now()->subDays(14),
        ];
    }
}
