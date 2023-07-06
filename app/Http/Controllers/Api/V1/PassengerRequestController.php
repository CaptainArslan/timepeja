<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;

class PassengerRequestController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validator = Validator::make(
            $request->all(),
            [
                'organization_id' => ['required', 'numeric', 'integer', 'exists:organizations,id'],
                'type' => ['required', 'string'],
                'name' => ['required', 'string'],
            ],
            [
                'organization_id.required' => 'organization id required',
                'organization_id.numeric' => 'organization id must be an integer',
                'type.required' => 'Request Types required',
                'type.string' => 'Type must be a string',
                'name.required' => 'Name is required',
                'name.string' => 'Name must be a string',
            ]
        );

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            $passenger =  auth('passenger')->user();

            $requests_data = new Requests();
            $requests_data->passenger_id = $passenger->id;
            $requests_data->organization_id = $request->organization_id;
            $requests_data->roll_no = $request->roll_no ?? null;
            $requests_data->class = $request->class ?? null;
            $requests_data->section = $request->section ?? null;
            $requests_data->profile_card = $request->profile_card ?? null;
            $requests_data->descipline = $request->descipline ?? null;
            $requests_data->designation = $request->designation ?? null;
            $requests_data->employee_comp_id = $request->employee_comp_id ?? null;
            $requests_data->route_id = $request->route_id ?? null;
            $requests_data->transport_start_time = $request->transport_start_time ?? null;
            $requests_data->transport_end_time = $request->transport_end_time ?? null;
            $requests_data->qualification = $request->qualification ?? null;
            $requests_data->batch_year = $request->batch_year ?? null;
            $requests_data->degree_duration = $request->degree_duration ?? null;
            $requests_data->type = $request->type ?? null;
            $save = $requests_data->save();
            if ($save) {
                if ($request->type == Requests::REQUEST_TYPE_STUDENT) {

                    $student = new Student();
                    $student->name = $request->name;
                    $student->request_id = $requests_data->id;
                    $student->phone = $request->phone ?? null;
                    $student->email = $request->email ?? null;
                    $student->image = $request->image ?? null;
                    $student->house_no = $request->house_no ?? null;
                    $student->street_no = $request->street_no ?? null;
                    $student->town = $request->town ?? null;
                    $student->additional_detail = $request->additional_detail ?? null;
                    $student->city_id = $request->city_id ?? null;
                    $student->pickup_address = $request->pickup_address ?? null;
                    $student->pickup_city_id = $request->pickup_city_id ?? null;
                    $student->lattitude = $request->lattitude ?? null;
                    $student->longitude = $request->longitude ?? null;
                    $student->type = $request->request_further_type ?? null;
                    // $student->status = $request->status ?? null;
                    $student->save();
                } else if ($request->type == Requests::REQUEST_TYPE_EMPLOYEE) {

                    $employee = new Employee();
                    $employee->name = $request->name;
                    $employee->request_id = $requests_data->id;
                    $employee->phone = $request->phone ?? null;
                    $employee->email = $request->email ?? null;
                    $employee->image = $request->image ?? null;
                    $employee->house_no = $request->house_no ?? null;
                    $employee->street_no = $request->street_no ?? null;
                    $employee->town = $request->town ?? null;
                    $employee->additional_detail = $request->additional_detail ?? null;
                    $employee->city_id = $request->city_id ?? null;
                    $employee->pickup_address = $request->pickup_address ?? null;
                    $employee->pickup_city_id = $request->pickup_city_id ?? null;
                    $employee->lattitude = $request->lattitude ?? null;
                    $employee->longitude = $request->longitude ?? null;
                    // $employee->status = $request->status ?? null;
                    $employee->save();
                }
                // else if ($request->type == 'guardian') {

                //     $guardian = new Guardian();
                //     $guardian->name = $request->name;
                //     $guardian->request_id = $requests_data->id;
                //     $guardian->phone = $request->phone ?? null;
                //     $guardian->email = $request->email ?? null;
                //     $guardian->image = $request->image ?? null;
                //     $guardian->house_no = $request->house_no ?? null;
                //     $guardian->street_no = $request->street_no ?? null;
                //     $guardian->town = $request->town ?? null;
                //     $guardian->additional_detail = $request->additional_detail ?? null;
                //     $guardian->city_id = $request->city_id ?? null;
                //     $guardian->cnic = $request->cnic ?? null;
                //     $guardian->cnic_front = $request->cnic_front ?? null;
                //     $guardian->cnic_back = $request->cnic_back ?? null;
                //     $guardian->guardian_code = $request->guardian_code ?? null;
                //     $guardian->pickup_address = $request->pickup_address ?? null;
                //     $guardian->pickup_city_id = $request->pickup_city_id ?? null;
                //     $guardian->latitude = $request->latitude ?? null;
                //     $guardian->longitude = $request->longitude ?? null;
                //     $guardian->relation = $request->relation ?? null;
                //     $guardian->status = $request->status ?? null;
                //     $guardian->save();
                // }
            }
            DB::commit();
            return $this->respondWithSuccess($requests_data, 'Request created successfully', 'REQUEST_CREATED_SUCCESSFULLY');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondWithError('Error Occured while request creation');
        }
    }


    public function createRequest($request)
    {
    }

    public function createStudent($request)
    {
    }

    public function createEmployee($request)
    {
    }

    public function createGuardian($request)
    {
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
}
