<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Passenger;
use Illuminate\Support\Str;
use App\Models\Organization;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Carbon;
use App\Models\Request as Requests;
use App\Models\Route;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement([
            Requests::STUDENT,
            Requests::EMPLOYEE,
            Requests::STUDENT_GUARDIAN,
            Requests::EMPLOYEE_GUARDIAN,
        ]);

        $student_type = null;
        $roll_no = null;
        $class = null;
        $section = null;
        $batch_year = null;
        $degree_duration = null;
        $discipline = null;
        $employee_comp_id = null;
        $designation = null;
        $cnic_no = null;
        $cnic_front_image = null;
        $cnic_back_image = null;
        $relation = null;
        $parent_request_id = null;

        if ($type == Requests::STUDENT) {
            $student_type = $this->faker->randomElement([
                Requests::SCHOOL,
                Requests::COLLEGE,
                Requests::UNIVERSITY,
            ]);
            $roll_no = $this->faker->randomNumber(5);
            $class = $this->faker->randomElement([
                'matric',
                'intermediate',
                'graduation',
                'masters',
                'phd',
            ]);
            $section = $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']);
            $batch_year = $this->faker->randomNumber(4);
            $degree_duration = $this->faker->randomElement(['2', '4', '6', '8']);
            $discipline = $this->faker->randomElement([
                'computer science',
                'software engineering',
                'information technology',
                'computer engineering',
                'electrical engineering',
            ]);
        } elseif ($type == Requests::EMPLOYEE) {
            $employee_comp_id = Str::random(8);
            $designation = $this->faker->randomElement([
                'software engineer',
                'senior software engineer',
                'project manager',
                'team lead',
                'senior team lead',
            ]);
        } elseif ($type == Requests::STUDENT_GUARDIAN || $type == Requests::EMPLOYEE_GUARDIAN) {
            $cnic_no = $this->faker->regexify('/^[0-9+]{13}$/');
            $cnic_front_image = $this->faker->imageUrl();
            $cnic_back_image = $this->faker->imageUrl();
            $relation = $this->faker->randomElement([
                'father',
                'mother',
                'brother',
                'sister',
                'uncle',
            ]);
        }

        if ($type == Requests::STUDENT_GUARDIAN) {
            $student_type = $this->faker->randomElement([
                Requests::SCHOOL,
                Requests::COLLEGE,
                Requests::UNIVERSITY,
            ]);
        }

        $commonFields = [
            'organization_id' => Organization::inRandomOrder()->first()->id,
            'parent_request_id' => $parent_request_id,
            'type' => $type,
            'student_type' => $student_type,
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            // Fill in other common fields
        ];

        $specificFields = [
            Requests::STUDENT => [
                'roll_no' => $roll_no,
                'class' => $class,
                'section' => $section,
                'batch_year' => $batch_year,
                'degree_duration' => $degree_duration,
                'discipline' => $discipline,
            ],
            Requests::EMPLOYEE => [
                'employee_comp_id' => $employee_comp_id,
                'designation' => $designation,
            ],
            Requests::STUDENT_GUARDIAN => [
                'cnic_no' => $cnic_no,
                'cnic_front_image' => $cnic_front_image,
                'cnic_back_image' => $cnic_back_image,
                'relation' => $relation,
            ],
            Requests::EMPLOYEE_GUARDIAN => [
                'cnic_no' => $cnic_no,
                'cnic_front_image' => $cnic_front_image,
                'cnic_back_image' => $cnic_back_image,
                'relation' => $relation,
            ],
        ];

        return array_merge(
            $commonFields,
            $specificFields[$type],
            [
                'name' => $this->faker->name(),
                'phone' => $this->faker->phoneNumber(),
                'passenger_id' => Passenger::inRandomOrder()->first()->id,
                'email' => $this->faker->email(),
                'address' => $this->faker->address(),
                'pickup_address' => $this->faker->address(),
                'house_no' => $this->faker->buildingNumber(),
                'street_no' => $this->faker->buildingNumber(),
                'town' => $this->faker->city(),
                'lattitude' => $this->faker->latitude(),
                'longitude' => $this->faker->longitude(),
                'pickup_city_id' => City::inRandomOrder()->first()->id,
                'additional_detail' => $this->faker->sentence(),
                'qualification' => $this->faker->randomElement([
                    'matric',
                    'intermediate',
                    'graduation',
                    'masters',
                    'phd',
                ]),
                'profile_card' => $this->faker->imageUrl(),
                'guardian_code' => Str::random(6),
                'route_id' => Route::inRandomOrder()->first()->id,
                'transport_start_date' => Carbon::now()->subDays(14),
                'transport_end_date' => Carbon::now()->subDays(14),
                'created_by' => $this->faker->randomElement([
                    'manager',
                    'passenger',
                    'admin',
                ]),
                'created_user_id' => $this->faker->randomNumber(5),
                'status' => $this->faker->randomElement([
                    Requests::STATUS_PENDING,
                    Requests::STATUS_APPROVED,
                    Requests::STATUS_DISAPPROVED,
                    Requests::STATUS_MEET_PERSONALLY,
                    Requests::STATUS_CANCELLED,
                    Requests::STATUS_DELETED,
                ]),
            ]
        );
    }
}
