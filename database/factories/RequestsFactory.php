<?php

namespace Database\Factories;

use App\Models\Route;
use App\Models\Requests;
use App\Models\Passenger;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement([
            Requests::REQUEST_TYPE_STUDENT,
            Requests::REQUEST_TYPE_EMPLOYEE,
            Requests::REQUEST_TYPE_GUARDIAN,
        ]);

        $roll_no = null;
        $class = null;
        $section = null;
        $profile_card = null;
        $descipline = null;
        $qualification = null;
        $batch_year = null;
        $degree_duration = null;
        $employee_comp_id = null;
        $designation = null;
        $transport_start_date = null;
        $transport_end_date = null;

        if ($type == Requests::REQUEST_TYPE_STUDENT) {
            $roll_no = $this->faker->unique()->randomNumber(6, true);
            $class = $this->faker->randomElement(['A', 'B', 'C']);
            $section = $this->faker->randomElement(['Section 1', 'Section 2', 'Section 3']);
            $profile_card = $this->faker->randomNumber(4, true);
            $descipline = $this->faker->randomElement(['Science', 'Arts', 'Commerce']);
            $qualification = $this->faker->randomElement(['Bachelor', 'Master']);
            $batch_year = $this->faker->numberBetween(2010, 2023);
            $degree_duration = $this->faker->numberBetween(3, 5);
        } else if ($type == Requests::REQUEST_TYPE_EMPLOYEE) {
            $employee_comp_id = $this->faker->unique()->randomNumber(4, true);
            $descipline = $this->faker->randomElement(['Engineering', 'Finance', 'Marketing']);
            $designation = $this->faker->jobTitle;
        } else if ($type == Requests::REQUEST_TYPE_GUARDIAN) {
            // Fill with random values based on the student case
            $studentData = $this->getRandomStudentData();
            $roll_no = $studentData['roll_no'];
            $class = $studentData['class'];
            $section = $studentData['section'];
            $profile_card = $studentData['profile_card'];
            $descipline = $studentData['descipline'];
            $qualification = $studentData['qualification'];
            $batch_year = $studentData['batch_year'];
            $degree_duration = $studentData['degree_duration'];
        }

        return [
            'passenger_id' => Passenger::inRandomOrder()->first()->id,
            'organization_id' => Organization::inRandomOrder()->first()->id,
            'roll_no' => $roll_no,
            'class' => $class,
            'section' => $section,
            'profile_card' => $profile_card,
            'descipline' => $descipline,
            'qualification' => $qualification,
            'batch_year' => $batch_year,
            'degree_duration' => $degree_duration,
            'employee_comp_id' => $employee_comp_id,
            'designation' => $designation,
            'route_id' => Route::inRandomOrder()->first()->id,
            'transport_start_date' => $transport_start_date,
            'transport_end_date' => $transport_end_date,
            'type' => $type,
            'status' => $this->faker->randomElement([
                Requests::REQUEST_STATUS_APPROVE,
                Requests::REQUEST_STATUS_DISAPPROVE,
                Requests::REQUEST_STATUS_MEET_PERSONALLY,
                Requests::REQUEST_STATUS_PENDING,
            ]),
        ];
    }

    private function getRandomStudentData()
    {
        $faker = \Faker\Factory::create();

        $roll_no = $faker->unique()->randomNumber(6, true);
        $class = $faker->randomElement(['A', 'B', 'C']);
        $section = $faker->randomElement(['Section 1', 'Section 2', 'Section 3']);
        $profile_card = $faker->randomNumber(4, true);
        $descipline = $faker->randomElement(['Science', 'Arts', 'Commerce']);
        $qualification = $faker->randomElement(['Bachelor', 'Master']);
        $batch_year = $faker->numberBetween(2010, 2023);
        $degree_duration = $faker->numberBetween(3, 5);

        return [
            'roll_no' => $roll_no,
            'class' => $class,
            'section' => $section,
            'profile_card' => $profile_card,
            'descipline' => $descipline,
            'qualification' => $qualification,
            'batch_year' => $batch_year,
            'degree_duration' => $degree_duration,
        ];
    }
}
