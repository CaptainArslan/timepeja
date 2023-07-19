<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Employee;
use App\Models\Guardian;
use App\Models\Passenger;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Request as Requests;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;

class RequestController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::where('status', Organization::STATUS_ACTIVE)->get();
        $requests = Requests::with('organization:id,name')
            ->with('student')
            ->with('employee')
            ->with('guardian')
            ->take(10)
            ->latest()
            ->get();
        // dd($requests->toArray());
        return view('request.index', [
            'organizations' => $organizations,
            'requests' => $requests,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'phone' => ['required', 'string',],
            'email' => ['required', 'email', 'string',],
            'house_no' => ['nullable', 'string',],
            'street_no' => ['nullable', 'string',],
            'town' => ['nullable', 'string',],
            'city_id' => ['nullable', 'string',],
            'pickup_address' => ['nullable', 'string',],
            'pickup_city_id' => ['nullable', 'string',],
            'lattitude' => ['nullable', 'string',],
            'longitude' => ['nullable', 'string',],
            'further_type' => ['nullable', 'string', 'in:school,college,university'],
            'image' => ['nullable', 'string',],
            'addtitional_detail' => ['nullable', 'string',],
            'organization_id' => ['required', 'numeric', 'exists:organizations,id'],
            'type' => ['required', 'string', 'in:student,employee,guardian'],
            'roll_no' => ['required', 'string',],
            'class' => ['required', 'string',],
            'section' => ['required', 'string',],
            'qualification' => ['required', 'string',],
            'batch_year' => ['nullable', 'integer',],
            'degree_duration' => ['nullable', 'integer',],
            'profile_card' => ['nullable', 'string',],
            'route_id' => ['nullable', 'numeric', 'exists:routes,id'],
            'transport_start_date' => ['nullable', 'date',],
            'transport_end_date' => ['nullable', 'date',],
        ], [
            'name.required' => 'Please enter your name.',
            'phone.required' => 'Please enter your phone number.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'further_type.required' => 'Please specify further type.',
            'organization_id.required' => 'Organization ID is required.',
            'type.required' => 'Please specify the type.',
            'roll_no.required' => 'Please enter your roll number.',
            'class.required' => 'Please enter your class.',
            'section.required' => 'Please enter your section.',
            'qualification.required' => 'Please enter your qualification.',
            'route_id.exists' => 'The selected route is invalid.',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError(implode(',', $validator->errors()->all()));
        }

        if ($request->has('unique_id')) {
            $passenger = Passenger::where('unique_id', $request->unique_id)->first();
            if ($passenger) {
                $passenger_id = $passenger->id;
            } else {
                return $this->respondWithError('Passenger unique Id not found.');
            }
        }
        try {
            $newRequestData = [
                'organization_id' => $request->organization_id,
                'passenger_id' => $passenger_id,
                'type' => $request->type,
                'roll_no' => $request->roll_no,
                'class' => $request->class,
                'section' => $request->section,
                'qualification' => $request->qualification,
                'batch_year' => $request->batch_year,
                'degree_duration' => $request->degree_duration,
                'descipline' => $request->descipline,
                'designation' => $request->designation,
                'profile_card' => $request->profile_card,
                'route_id' => $request->route_id,
                'transport_start_date' => $request->transport_start_date,
                'transport_end_date' => $request->transport_end_date,
                'status' => Requests::REQUEST_STATUS_PENDING, // You can set the default value here or in the migration.
            ];

            DB::beginTransaction();

            if ($request->type == 'student') {
                $student = $this->createStudent($request);
                $newRequestData['student_id'] = $student->id;
            } elseif ($request->type == 'employee') {
                $employee = $this->createEmployee($request);
                $newRequestData['employee_id'] = $employee->id;
            }

            $newRequest = Requests::create($newRequestData);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondWithError('Error Occured while creating request');
        }
        return $this->respondWithSuccess(null, 'Request Created Successfully', 'REQUEST_CREATED_SUCCESSFULLY');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * function to create student
     *
     * @param [object] $request
     * @return void
     */
    public function createStudent($request)
    {
        $student = new Student();
        $student->name = $request->name;
        $student->phone = $request->phone;
        $student->email = $request->email;
        $student->house_no = $request->house_no;
        $student->street_no = $request->street_no;
        $student->town = $request->town;
        $student->city_id = $request->city_id;
        $student->pickup_address = $request->pickup_address;
        $student->pickup_city_id = $request->pickup_city_id;
        $student->lattitude = $request->lattitude;
        $student->longitude = $request->longitude;
        $student->further_type = $request->further_type;
        $student->image = $request->image;
        $student->additional_detail = $request->additional_detail;
        $student->save();
        return $student;
    }

    /**
     * Create Employee
     *
     * @param [object] $request
     * @return void
     */
    public function createEmployee($request)
    {
        $employee = new Employee();
        $employee->name = $request->name;
        $employee->phone = $request->phone;
        $employee->email = $request->email;
        $employee->house_no = $request->house_no;
        $employee->street_no = $request->street_no;
        $employee->town = $request->town;
        $employee->city_id = $request->city_id;
        $employee->pickup_address = $request->pickup_address;
        $employee->pickup_city_id = $request->pickup_city_id;
        $employee->lattitude = $request->lattitude;
        $employee->longitude = $request->longitude;
        // $employee->further_type = $request->further_type;
        $employee->image = $request->image;
        $employee->additional_detail = $request->additional_detail;
        $employee->save();
        return $employee;
    }

    public function createGuardian($request)
    {
        $guardian = new Guardian();
        $guardian->name = $request->name;
        $guardian->save();
        return $guardian;
    }
}
