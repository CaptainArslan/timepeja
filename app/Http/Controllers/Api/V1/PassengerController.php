<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;

class PassengerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $manager = auth('passenger')->user();
            $driver = Passenger::where('o_id', $manager->o_id)
                ->where('status', Passenger::STATUS_ACTIVE)
                ->paginate(Passenger::PASSENGER_LIMIT_PER_PAGE);
            return $this->respondWithSuccess($driver, 'All passenger', 'TOTAL_PASSENGER');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while fetching passenger' . $th->getMessage());
        }
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
            'phone' => ['required', 'numeric', 'digits:11', 'unique:passengers,phone',],
            'verification_code' => ['required'],
            'password' => ['required', 'min:8', 'max:25', 'confirmed',],
        ], [
            'o_id.required' => 'Organization required',
            'name.required' => 'Name required',
            'phone.required' => 'Phone required',
            'phone.unique' => 'Phone already exists',
            'cnic.required' => 'CNIC required',
            'cnic.unique' => 'CNIC already exists',
            'cnic.regex' => 'CNIC format is invalid',
            'license_no.required' => 'License required',
            'license_no.unique' => 'License already exists',
            'license_no.regex' => 'License format is invalid, must be 10 digit number and 3 character string eg 1234567890-ABC',
            'cnic_front.required' => 'CNIC front required',
            'cnic_front.image' => 'CNIC front must be an image',
            'cnic_front.mimes' => 'CNIC front must be a file of type: jpeg, png, jpg, gif, svg',
            'cnic_front.max' => 'CNIC front may not be greater than 2048 kilobytes',
            'cnic_back.required' => 'CNIC back required',
            'cnic_back.image' => 'CNIC back must be an image',
            'cnic_back.mimes' => 'CNIC back must be a file of type: jpeg, png, jpg, gif, svg',
            'cnic_back.max' => 'CNIC back may not be greater than 2048 kilobytes',
            'license_front.required' => 'License front required',
            'license_front.image' => 'License front must be an image',
            'license_front.mimes' => 'License front must be a file of type: jpeg, png, jpg, gif, svg',
            'license_front.max' => 'License front may not be greater than 2048 kilobytes',
            'license_back.required' => 'License back required',
            'license_back.image' => 'License back must be an image',
            'license_back.mimes' => 'License back must be a file of type: jpeg, png, jpg, gif, svg',
            'license_back.max' => 'License back may not be greater than 2048 kilobytes',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }
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

    public function updatePhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => [
                'required',
                'numeric',
                'digits:11',
                Rule::unique('passengers', 'phone')->ignore(auth('passenger')->id()), // Ignore the current passenger's record
            ],
            'otp' => ['required'], // Add validation for 'top' field
        ]);

        if ($validator->fails()) {
            return $this->respondWithError(implode(", ", $validator->errors()->all()));
        }

        try {
            $passenger = auth('passenger')->user();

            // Check if the 'top' value from the request matches the passenger's 'top' column
            if ($request->otp === $passenger->otp) {
                // Verification succeeded, update the phone number
                $passenger->phone = $request->phone;
                $passenger->save();

                return $this->respondWithSuccess($passenger, 'Passenger phone number updated successfully', 'PASSENGER_PHONE_UPDATED_SUCCESSFULLY');
            } else {
                // Verification failed
                return $this->respondWithError('Invalid otp');
            }
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occurred while updating passenger phone number' . $th->getMessage());
        }
    }
}
