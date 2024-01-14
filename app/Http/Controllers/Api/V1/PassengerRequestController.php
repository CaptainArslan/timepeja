<?php

namespace App\Http\Controllers\Api\V1;


use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Models\Request as Requests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PassengerRequestController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        try {
            $passenger = auth('passenger')->user();

            $requestStatuses = [
                Requests::STATUS_APPROVED,
                Requests::STATUS_PENDING,
                Requests::STATUS_MEET_PERSONALLY
            ];

            $allRequests = Requests::where('passenger_id', $passenger->id)
                ->with([
                    'city:id,name',
                    'route:id,name',
                    'organization:id,name,branch_name,branch_code,email,phone,code',
                ])
                // ->with('childRequests')
                ->whereIn('status', $requestStatuses)
                ->latest()
                ->get();

            return $this->respondWithSuccess($allRequests, 'Request Lists', 'REQUEST_LISTS');
        } catch (\Throwable $th) {
            // Log the exception for debugging
            Log::error('Error occurred while fetching request list: ' . $th->getMessage());
            return $this->respondWithError('Error occurred while fetching request list');
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
        $validator = Validator::make($request->all(),  [
            'organization_id' => ['required', 'numeric', 'exists:organizations,id',],
            // 'parent_request_id' => ['nullable', 'numeric', 'exists:requests,id',],
            'type' => ['required', 'string', 'in:student,employee,student_guardian,employee_guardian',],
            'student_type' => ['nullable', 'string', 'in:school,college,university', Rule::requiredIf(function () use ($request) {
                return in_array($request->type, ['student', 'student_guardian']);
            }),],
            'gender' => ['nullable', 'string', 'in:male,female,other',],
            'name' => ['required', 'string',],
            'phone' => ['required', 'string',],
            // 'passenger_id' => ['required', 'numeric', 'exists:passengers,id',],
            'email' => ['nullable', 'email', 'string',],
            'address' => ['required', 'string',],
            'pickup_address' => ['nullable', 'string',],
            'house_no' => ['nullable', 'string',],
            'street_no' => ['required', 'string',],
            'town' => ['nullable', 'string',],
            'lattitude' => ['nullable', 'string',],
            'longitude' => ['nullable', 'string',],
            'pickup_city_id' => ['nullable', 'string',],
            'additional_detail' => ['nullable', 'string',],
            'roll_no' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student']);
                }),
            ],
            'class' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student']);
                }),
            ],
            'section' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student']);
                }),
            ],
            'qualification' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student',]);
                }),
            ],
            'batch_year' => [
                'nullable', 'string',
                // Rule::requiredIf(function () use ($request) {
                //     return in_array($request->type, ['student',]);
                // }),
            ],
            'degree_duration' => [
                'nullable', 'string',
                // Rule::requiredIf(function () use ($request) {
                //     return in_array($request->type, ['student',]);
                // }),
            ],
            'discipline' => [
                'nullable', 'string',
                // Rule::requiredIf(function () use ($request) {
                //     return in_array($request->type, ['student',]);
                // }),
            ],
            'employee_comp_id' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['employee',]);
                }),
            ],
            'designation' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['employee',]);
                })
            ],
            'profile_card' => [
                'nullable', 'string',
                // Rule::requiredIf(function () use ($request) {
                //     return in_array($request->type, ['student','employee','student_guardian','employee_guardian']);
                // }),
            ],
            'cnic_no' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student_guardian', 'employee_guardian']);
                }),
            ],
            'cnic_front_image' => [
                'nullable',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student_guardian', 'employee_guardian']);
                }),
            ],
            'cnic_back_image' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student_guardian', 'employee_guardian']);
                })
            ],
            'relation' => [
                'nullable', 'string', 'in:father,mother,uncle,aunt,brother,sister,grandfather,grandmother,other',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student_guardian', 'employee_guardian']);
                }),
            ],
            'guardian_code' => [
                'nullable', 'string', 'exists:requests,guardian_code',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student_guardian', 'employee_guardian']);
                })
            ],
            'route_id' => ['nullable', 'numeric', 'exists:routes,id'],
            'transport_start_date' => [
                'nullable', 'date',
                // 'required_if:route_id,!=,null'
            ],
            'transport_end_date' => [
                'nullable', 'date',
                // 'required_if:route_id,!=,null'
            ],
            'status' => ['nullable', 'string', 'in:pending,approved,disapproved'],
        ], [
            'name.required' => 'Please enter your name.',
            'phone.required' => 'Please enter your phone number.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'student_type.required' => 'Please specify Student type.',
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

        $request_id =  null;
        $organization_id = $request->organization_id;
        $passenger = auth('passenger')->user();
        $student_type = $request->student_type;

        if ($request->type === 'student_guardian' || $request->type === 'employee_guardian') {
            $parentRequest = Requests::where('guardian_code', $request->guardian_code)->first();
            $childRequestCount = $parentRequest->childRequests->count();
            $student_type = $parentRequest->student_type;

            if ($childRequestCount >= Requests::MAX_GUARDIAN_ALLOWED) {
                return $this->respondWithError('You cannot add more than 3 guardians.');
            }

            $request_id = $parentRequest->id;
            $organization_id = $parentRequest->organization_id;
        }

        $data = $request->all();
        $data['passenger_id'] = $passenger->id;
        $data['student_type'] = $student_type;
        $data['guardian_code'] = substr(uniqid(), -8);
        $data['parent_request_id'] = $request_id;
        $data['organization_id'] = $organization_id;
        $data['created_by'] = 'passenger';
        $data['created_user_id'] = $passenger->id;
        $data['status'] = Requests::STATUS_APPROVED;

        $data = Requests::create($data);

        return $this->respondWithSuccess($data, 'Request Created Successfully', 'REQUEST_CREATED_SUCCESSFULLY');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $request = Requests::with([
                'city:id,name',
                'route:id,name',
                'organization:id,name,branch_name,branch_code,email,phone,code'
            ])->findOrFail($id);

            return $this->respondWithSuccess($request, 'Request Details', 'REQUEST_DETAILS');
        } catch (\Throwable $th) {
            Log::error('Error occurred while fetching request list: ' . $th->getMessage());
            return $this->respondWithError('Error occurred while fetching request details');
        }
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
        $validator = Validator::make($request->all(),  [
            'organization_id' => ['required', 'numeric', 'exists:organizations,id',],
            // 'parent_request_id' => ['nullable', 'numeric', 'exists:requests,id',],
            'type' => ['required', 'string', 'in:student,employee,student_guardian,employee_guardian',],
            'student_type' => ['nullable', 'string', 'in:school,college,university', Rule::requiredIf(function () use ($request) {
                return in_array($request->type, ['student', 'student_guardian']);
            }),],
            'gender' => ['nullable', 'string', 'in:male,female,others',],
            'name' => ['required', 'string',],
            'phone' => ['required', 'string',],
            // 'passenger_id' => ['required', 'numeric', 'exists:passengers,id',],
            'email' => ['nullable', 'email', 'string',],
            'address' => ['required', 'string',],
            'pickup_address' => ['nullable', 'string',],
            'house_no' => ['nullable', 'string',],
            'street_no' => ['required', 'string',],
            'town' => ['nullable', 'string',],
            'lattitude' => ['nullable', 'string',],
            'longitude' => ['nullable', 'string',],
            'pickup_city_id' => ['nullable', 'string'],
            'additional_detail' => ['nullable', 'string',],
            'roll_no' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student']);
                }),
            ],
            'class' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student']);
                }),
            ],
            'section' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student']);
                }),
            ],
            'qualification' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student',]);
                }),
            ],
            'batch_year' => [
                'nullable', 'string',
                // Rule::requiredIf(function () use ($request) {
                //     return in_array($request->type, ['student',]);
                // }),
            ],
            'degree_duration' => [
                'nullable', 'string',
                // Rule::requiredIf(function () use ($request) {
                //     return in_array($request->type, ['student',]);
                // }),
            ],
            'discipline' => [
                'nullable', 'string',
                // Rule::requiredIf(function () use ($request) {
                //     return in_array($request->type, ['student',]);
                // }),
            ],
            'employee_comp_id' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['employee',]);
                }),
            ],
            'designation' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['employee',]);
                })
            ],
            'profile_card' => [
                'nullable', 'string',
                // Rule::requiredIf(function () use ($request) {
                //     return in_array($request->type, ['student','employee','student_guardian','employee_guardian']);
                // }),
            ],
            'cnic_no' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student_guardian', 'employee_guardian']);
                }),
            ],
            'cnic_front_image' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student_guardian', 'employee_guardian']);
                }),
            ],
            'cnic_back_image' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student_guardian', 'employee_guardian']);
                })
            ],
            'relation' => [
                'nullable', 'string',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student_guardian', 'employee_guardian']);
                }),
                'in:father,mother,uncle,aunt,brother,sister,grandfather,grandmother,other'
            ],
            'guardian_code' => [
                'nullable', 'string', 'exists:requests,guardian_code',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->type, ['student_guardian', 'employee_guardian']);
                })
            ],
            'route_id' => ['nullable', 'numeric', 'exists:routes,id'],
            'transport_start_date' => [
                'nullable', 'date',
                // 'required_if:route_id,!=,null'
            ],
            'transport_end_date' => [
                'nullable', 'date',
                // 'required_if:route_id,!=,null'
            ],
            'status' => ['nullable', 'string', 'in:pending,approved,disapproved'],
        ], [
            'name.required' => 'Please enter your name.',
            'phone.required' => 'Please enter your phone number.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'student_type.required' => 'Please specify Student type.',
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

        try {
            $request_found = Requests::findOrFail($id);

            $request_id =  null;
            $organization_id = $request->organization_id;
            $passenger = auth('passenger')->user();
            $data = $request->all();

            if ($request->type === 'student_guardian' || $request->type === 'employee_guardian') {
                $parentRequest = Requests::where('guardian_code', $request->guardian_code)->first();
                $childRequestCount = $parentRequest->childRequests->count();

                if ($childRequestCount >= Requests::MAX_GUARDIAN_ALLOWED) {
                    return $this->respondWithError('You cannot add more than 3 guardians.');
                }

                $data['parent_request_id'] = $parentRequest->id;
                $data['organization_id'] = $parentRequest->organization_id;
            }


            $data['passenger_id'] = $passenger->id;
            $data['guardian_code'] = substr(uniqid(), -8);
            $data['created_by'] = 'passenger';
            $data['created_user_id'] = $passenger->id;
            $data['status'] = Requests::STATUS_APPROVED;

            $request_found::update($data);

            return $this->respondWithSuccess($data, 'Request Created Successfully', 'REQUEST_CREATED_SUCCESSFULLY');
        } catch (\Throwable $th) {
            Log::error('Error occurred while fetching request list: ' . $th->getMessage());
            return $this->respondWithError('Error occurred while updating request details');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Requests::findOrFail($id)->delete();
            return $this->respondWithSuccess(null, 'Request Details', 'REQUEST_DETAILS');
        } catch (\Throwable $th) {
            Log::error('Error occurred while Deleting route: ' . $th->getMessage());
            return $this->respondWithError('Error occurred while fetching request details');
        }
    }

    public function getRequestDetailByCode($code){
        try {
            $request = Requests::with('organization:id,name')
                ->with('city:id,name')
                ->with('route:id,name')
                ->with('passenger:id,name,phone')
                ->where('guardian_code', $code)
                ->firstOrFail();

            return $this->respondWithSuccess($request, 'Request Details', 'REQUEST_SPECIFIC_DETAILS');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while fetching request details');
        }
    }
}
