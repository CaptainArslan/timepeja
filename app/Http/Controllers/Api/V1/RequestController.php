<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Response;
use Throwable;
use App\Models\Student;
use App\Models\Employee;
use App\Models\Guardian;
use App\Models\Passenger;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Request as Requests;
use Illuminate\Support\Facades\Validator;

class RequestController extends BaseController
{

    public function index(): JsonResponse
    {
        try {
            $manager_id = auth('manager')->user();

            $allRequests = Requests::with('organization:id,name')
                ->with('city:id,name')
                ->with('route:id,name')
                // ->with('childRequests')
                ->with('passenger:id,name,phone')
                ->where('organization_id', $manager_id->o_id)
                ->whereIn('status', [Requests::STATUS_APPROVED, Requests::STATUS_PENDING]) // Include both statuses
                ->withCount('childRequests')
                ->latest()
                ->get();

            $approvedRequests = [];
            $pendingRequests = [];

            foreach ($allRequests as $request) {
                if ($request->status === Requests::STATUS_APPROVED) {
                    $approvedRequests[] = $request;
                } elseif ($request->status === Requests::STATUS_PENDING) {
                    $pendingRequests[] = $request;
                }
            }

            $response = [
                'approved_requests' => $approvedRequests,
                'pending_requests' => $pendingRequests,
            ];

            return $this->respondWithSuccess($response, 'Request Lists', 'REQUEST_LISTS');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occurred while fetching request list' . $th->getMessage());
        }
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => ['required', 'string'],
    //         'phone' => ['required', 'string',],
    //         'email' => ['required', 'email', 'string',],
    //         'house_no' => ['nullable', 'string',],
    //         'street_no' => ['nullable', 'string',],
    //         'town' => ['nullable', 'string',],
    //         'city_id' => ['nullable', 'string',],
    //         'pickup_address' => ['nullable', 'string',],
    //         'pickup_city_id' => ['nullable', 'string',],
    //         'lattitude' => ['nullable', 'string',],
    //         'longitude' => ['nullable', 'string',],
    //         'student_type' => ['nullable', 'string', 'in:school,college,university'],
    //         'image' => ['nullable', 'string',],
    //         'addtitional_detail' => ['nullable', 'string',],
    //         'organization_id' => ['required', 'numeric', 'exists:organizations,id'],
    //         'type' => ['required', 'string', 'in:student,employee,student_guardian,employee_guardian'],
    //         'roll_no' => ['nullable', 'string',],
    //         'class' => ['nullable', 'string',],
    //         'section' => ['nullable', 'string',],
    //         'qualification' => ['nullable', 'string',],
    //         'batch_year' => ['nullable', 'integer',],
    //         'degree_duration' => ['nullable', 'integer',],
    //         'profile_card' => ['nullable', 'string',],
    //         'route_id' => ['nullable', 'numeric', 'exists:routes,id'],
    //         'transport_start_date' => ['nullable', 'date',],
    //         'transport_end_date' => ['nullable', 'date',],
    //         'guardian_type' => ['nullable', 'string'],
    //         'guardian_code' => [
    //             'nullable',
    //             'string',
    //             Rule::requiredIf(function () use ($request) {
    //                 return in_array($request->input('type'), ['student_guardian', 'employee_guardian']);
    //             }),
    //         ],
    //         'cnic_no' => [
    //             'nullable',
    //             'string',
    //             Rule::requiredIf(function () use ($request) {
    //                 return in_array($request->input('type'), ['student_guardian', 'employee_guardian']);
    //             }),
    //         ],
    //         'cnic_front' => [
    //             'nullable',
    //             'string',
    //             Rule::requiredIf(function () use ($request) {
    //                 return in_array($request->input('type'), ['student_guardian', 'employee_guardian']);
    //             }),
    //         ],
    //         'cnic_back' => [
    //             'nullable',
    //             'string',
    //             Rule::requiredIf(function () use ($request) {
    //                 return in_array($request->input('type'), ['student_guardian', 'employee_guardian']);
    //             }),
    //         ],
    //         'relation' => [
    //             'nullable',
    //             'string',
    //             Rule::requiredIf(function () use ($request) {
    //                 return in_array($request->input('type'), ['student_guardian', 'employee_guardian']);
    //             }),
    //         ],
    //     ], [
    //         'name.required' => 'Please enter your name.',
    //         'phone.required' => 'Please enter your phone number.',
    //         'email.required' => 'Please enter your email address.',
    //         'email.email' => 'Please enter a valid email address.',
    //         'student_type.required' => 'Please specify further type.',
    //         'organization_id.required' => 'Organization ID is required.',
    //         'type.required' => 'Please specify the type.',
    //         'roll_no.required' => 'Please enter your roll number.',
    //         'class.required' => 'Please enter your class.',
    //         'section.required' => 'Please enter your section.',
    //         'qualification.required' => 'Please enter your qualification.',
    //         'route_id.exists' => 'The selected route is invalid.',
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->respondWithError(implode(',', $validator->errors()->all()));
    //     }

    //     if ($request->has('unique_id') && !empty($request->unique_id)) {
    //         $passenger = Passenger::where('unique_id', $request->unique_id)->first();

    //         if ($passenger) {
    //             $passenger_id = $passenger->id;
    //         } else {
    //             return $this->respondWithError('Passenger unique Id not found.');
    //         }
    //     }
    //     else if ($request->has('guardian_code') && !empty($request->guardian_code)) {
    //         $already_created_request = Requests::where('guardian_code', $request->guardian_code)->first();

    //         if (!empty($already_created_request)) {
    //             $already_request = $already_created_request;
    //             // $already_created_request_id = $already_created_request->id;
    //         } else {
    //             return $this->respondWithError('Guardian code not found.');
    //         }
    //     }

    //     $newRequestData = $request->toArray();
    //     if ($request->type == 'student') {
    //         $student = $this->createStudent($request);
    //         $newRequestData['student_id'] = $student->id;
    //         $requestCreate = $this->createRequest($newRequestData, $passenger_id);
    //     } elseif ($request->type == 'employee') {
    //         $employee = $this->createEmployee($request);
    //         $newRequestData['employee_id'] = $employee->id;
    //         $requestCreate = $this->createRequest($newRequestData, $passenger_id);
    //     } else if ($request->type == 'student_guardian') {

    //         $newRequestData['student_id'] = $already_request->student_id;

    //         $count = Requests::where('student_id', $already_request->student_id)->count();
    //         if ($count > 3) {
    //             return $this->respondWithError('You can not add more than 3 guardians.');
    //         }
    //         $guardian = $this->createGuardian($newRequestData);
    //         $requestCreate = $this->createRequest($newRequestData, $already_request->passenger_id);

    //         $guardian->students()->attach($already_request->student_id);
    //         $guardian->requests()->attach($requestCreate->id);

    //         // $requestCreate->guardians()->attach($guardian->id);
    //     } elseif ($request->type == 'employee_guardian') {

    //         $count = Requests::where('employee_id', $already_request->employee_id)->count();
    //         if ($count > 3) {
    //             return $this->respondWithError('You can not add more than 3 guardians.');
    //         }
    //         $newRequestData['employee_id'] = $already_request->employee_id;
    //         $guardian = $this->createGuardian($newRequestData);
    //         $requestCreate = $this->createRequest($newRequestData, $already_request->passenger_id);

    //         $guardian->employees()->attach($already_request->employee_id);
    //         $guardian->requests()->attach($requestCreate->id);
    //     }

    //     try {
    //         DB::beginTransaction();
    //         DB::commit();
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         return $this->respondWithError('Error Occured while creating request');
    //     }
    //     return $this->respondWithSuccess(null, 'Request Created Successfully', 'REQUEST_CREATED_SUCCESSFULLY');
    // }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'unique_id' => ['required', 'string', 'exists:passengers,unique_id',],
                    'organization_id' => ['nullable', 'numeric', 'exists:organizations,id',],
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
                        // Rule::requiredIf(function () use ($request) {
                        //     return in_array($request->type, ['student']);
                        // }),
                    ],
                    'section' => [
                        'nullable', 'string',
                        // Rule::requiredIf(function () use ($request) {
                        //     return in_array($request->type, ['student']);
                        // }),
                    ],
                    'qualification' => [
                        'nullable', 'string',
                        // Rule::requiredIf(function () use ($request) {
                        //     return in_array($request->type, ['student',]);
                        // }),
                    ],
                    'batch_year' => [
                        'nullable', 'integer',
                        // Rule::requiredIf(function () use ($request) {
                        //     return in_array($request->type, ['student',]);
                        // }),
                    ],
                    'degree_duration' => [
                        'nullable',
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
                        'nullable', 'date_format:Y-m-d',
                        // 'required_if:route_id,!=,null'
                    ],
                    'transport_end_date' => [
                        'nullable', 'date_format:Y-m-d',
                        // 'required_if:route_id,!=,null'
                    ],
                    'status' => ['nullable', 'string', 'in:pending,approved,disapproved'],
                ],
                [
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
                ]
            );

            if ($validator->fails()) {
                return $this->respondWithError(implode(',', $validator->errors()->all()));
            }

            $manager = auth('manager')->user();

            $request_id = null;
            $organization_id = $manager->o_id;
            $passenger = Passenger::where('unique_id', $request->unique_id)->first();
            $passenger_id = $passenger->id;
            if ($request->type === 'student_guardian' || $request->type === 'employee_guardian') {
                $parentRequest = Requests::where('guardian_code', $request->guardian_code)->first();
                $childRequestCount = $parentRequest->childRequests->count();

                if ($childRequestCount >= Requests::MAX_GUARDIAN_ALLOWED) {
                    return $this->respondWithError('You cannot add more than 3 guardians.');
                }

                $request_id = $parentRequest->id;
                $organization_id = $parentRequest->organization_id;
            }

            $data = $request->all();
            $data['passenger_id'] = $passenger_id;
            $data['upload_image'] = $request->upload_image;
            $data['guardian_code'] = Str::random(6);
            $data['parent_request_id'] = $request_id;
            $data['organization_id'] = $organization_id;
            $data['created_by'] = 'manager';
            $data['created_user_id'] = auth('manager')->user()->id;
            $data['status'] = Requests::STATUS_APPROVED;

            $data = Requests::create($data);

            if($passenger->device_token) {
                notification('Request Created', 'Your request has been created successfully', $passenger->device_token);
            }

            return $this->respondWithSuccess($data, 'Request Created Successfully', 'REQUEST_CREATED_SUCCESSFULLY');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while creating request');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $requests = Requests::with('childRequests')->withCount('childRequests')->with('city:id,name')
                ->with('route:id,name')
                // ->with('childRequests')
                ->with('passenger:id,name,phone')
                ->with('organization:id,name,branch_name,branch_code,email,phone,code')->findOrFail($id);
            return $this->respondWithSuccess($requests, 'Request Details', 'REQUEST_DETAILS');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while fetching request details');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Delete Requests
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_ids' => ['required', 'array'],
            'request_ids.*' => ['integer', 'exists:requests,id'], // Assuming your table name is "requests"
        ], [
            'request_ids.required' => 'Request ids are required',
            'request_ids.*.integer' => 'ID must be an integer',
            'request_ids.*.exists' => 'Invalid ID provided',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        $requestIds = (array)$request->request_ids;

        try {
            Requests::whereIn('id', $requestIds)->update([
                'status' => Requests::STATUS_DELETED,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->respondWithSuccess([], 'Requests deleted successfully', 'REQUESTS_DELETED_SUCCESSFULLY');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occurred while deleting requests');
        }
    }

    /**
     * Delete Requests
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function approveRequests(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'request_ids' => ['required', 'array'],
            'request_ids.*' => ['integer', 'exists:requests,id'], // Assuming your table name is "requests"
        ], [
            'request_ids.required' => 'Request ids are required',
            'request_ids.*.integer' => 'ID must be an integer',
            'request_ids.*.exists' => 'Invalid ID provided',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        $requestIds = (array)$request->request_ids;

        try {
            DB::transaction(function () use ($requestIds) {
                foreach ($requestIds as $requestId) {
                    $request = Requests::findOrFail($requestId);
                    $save = $request->update(['status' => 'approved', 'deleted_at' => null]);

                    if (!$save) {
                        return $this->respondWithError('Error Occurred while approving requests');
                    }

                    $passenger = Passenger::find($request->passenger_id);
                    if ($passenger) {
                        $passengerToken = $passenger->device_token;
                        $name = ucfirst($passenger->name);
                        if ($passengerToken) {
                            notification('Request Approved', `Dear {$name}! Contratulation... Your request has been approved`, $passengerToken);
                        }
                    }
                }
                return true;
            });
            // Update the status of the requests to 'approved'
            // $request = Requests::whereIn('id', $requestIds)->update(['status' => 'approved', 'deleted_at' => null]);
            return $this->respondWithSuccess([], 'Requests approved successfully', 'REQUESTS_APPROVED_SUCCESSFULLY');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occurred while approving requests');
        }
    }

    /**
     * Delete Requests
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function disapproveRequests(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'request_ids' => ['required', 'array'],
            'request_ids.*' => ['integer', 'exists:requests,id'], // Assuming your table name is "requests"
        ], [
            'request_ids.required' => 'Request ids are required',
            'request_ids.*.integer' => 'ID must be an integer',
            'request_ids.*.exists' => 'Invalid ID provided',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        $requestIds = $request->request_ids;

        try {
            DB::transaction(function () use ($requestIds) {
                foreach ($requestIds as $requestId) {
                    $request = Requests::findOrFail($requestId);
                    $save = $request->update(['status' => 'disapproved', 'deleted_at' => null]);

                    if (!$save) {
                        return $this->respondWithError('Error Occurred while approving requests');
                    }

                    $passenger = Passenger::find($request->passenger_id);
                    if ($passenger) {
                        $passengerToken = $passenger->device_token;
                        $name = ucfirst($passenger->name);
                        if ($passengerToken) {
                            notification('Request Disapproved', `Dear {$name}! Sadly... Your request has been disapproved`, $passengerToken);
                        }
                    }
                }
                return true;
            });
            return $this->respondWithSuccess([], 'Requests disapproved successfully', 'REQUESTS_DISAPPROVED_SUCCESSFULLY');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occurred while dissapproving requests');
        }
    }

    /**
     * Delete Requests
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function meetPersonallyRequests(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'request_ids' => ['required', 'array'],
            'request_ids.*' => ['integer', 'exists:requests,id'], // Assuming your table name is "requests"
        ], [
            'request_ids.required' => 'Request ids are required',
            'request_ids.*.integer' => 'ID must be an integer',
            'request_ids.*.exists' => 'Invalid ID provided',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        $requestIds = $request->request_ids;

        try {
            // Update the status of the requests to 'approved'
            DB::transaction(function () use ($requestIds) {
                foreach ($requestIds as $requestId) {
                    $request = Requests::findOrFail($requestId);
                    $save = $request->update(['status' => 'meet-personally', 'deleted_at' => null]);

                    if (!$save) {
                        return $this->respondWithError('Error Occurred while approving requests');
                    }

                    $passenger = Passenger::find($request->passenger_id);
                    if ($passenger) {
                        $passengerToken = $passenger->device_token;
                        $name = ucfirst($passenger->name);
                        if ($passengerToken) {
                            notification('Personal meeting for request', `Dear {$name}! Please {$name} come to our office we want to meet you personally`, $passengerToken);
                        }
                    }
                }
                return true;
            });
            // Requests::whereIn('id', $requestIds)->update(['status' => 'meet-personally', 'deleted_at' => null]);
            return $this->respondWithSuccess([], 'Requests status updated!', 'REQUESTS_MEET_PERSONALLY_SUCCESSFULLY');
        } catch (Throwable $th) {
            return $this->respondWithError('Error Occurred while updating meet-personal requests');
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function past(Request $request): JsonResponse
    {
        try {
            $manager_id = auth('manager')->user();

            $limit = $request->limit ?? Requests::LIMIT;

            $allRequests = Requests::with('organization:id,name')
                ->with('city:id,name')
                ->with('route:id,name')
                ->with('passenger:id,name,phone')
                ->where('organization_id', $manager_id->o_id)
                ->withCount('childRequests')
                ->Where('status', Requests::STATUS_DELETED)
                // ->onlyTrashed()
                ->latest()
                ->paginate($limit);

            return $this->respondWithSuccess($allRequests, 'Past User Request Lists', 'PAST_USER_REQUEST_LISTS');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occurred while fetching request list' . $th->getMessage());
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function disapproved(Request $request): JsonResponse
    {
        try {
            $manager_id = auth('manager')->user();

            $limit = $request->limit ?? getPaginated();

            $allRequests = Requests::with('organization:id,name')
                ->with('city:id,name')
                ->with('route:id,name')
                // ->with('childRequests')
                ->with('passenger:id,name,phone')
                ->where('organization_id', $manager_id->o_id)
                ->where('status', Requests::STATUS_DISAPPROVED) // Include both statuses
                ->withCount('childRequests')
                ->latest()
                ->paginate($limit);

            return $this->respondWithSuccess($allRequests, 'Past User Request Lists', 'PAST_USER_REQUEST_LISTS');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occurred while fetching request list' . $th->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
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
        $student->student_type = $request->student_type;
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
        // $employee->student_type = $request->student_type;
        $employee->image = $request->image;
        $employee->additional_detail = $request->additional_detail;
        $employee->save();
        return $employee;
    }

    /**
     * Create Guardian
     *
     * @param [type] $request
     * @return void
     */
    public function createGuardian($request)
    {

        // dd($request);
        $guardian = new Guardian();
        // $guardian->request_id = $request_id;
        // $guardian->student_id = isset($request['student_id']) ? $request['student_id'] : null;
        // $guardian->employee_id = isset($request['employee_id']) ? $request['employee_id'] : null;
        $guardian->name = $request['name'];
        $guardian->image = $request['image'];
        $guardian->phone = $request['phone'];
        $guardian->house_no = $request['house_no'];
        $guardian->street_no = $request['street_no'];
        $guardian->town = $request['town'];
        $guardian->city_id = $request['city_id'];
        $guardian->cnic = $request['cnic_no'];
        $guardian->cnic_front = $request['cnic_front'];
        $guardian->cnic_back = $request['cnic_back'];
        $guardian->pickup_address = $request['pickup_address'];
        $guardian->pickup_city_id = $request['pickup_city_id'];
        $guardian->lattitude = $request['lattitude'];
        $guardian->longitude = $request['longitude'];
        $guardian->relation = $request['relation'];
        $guardian->guardian_code = $request['guardian_code'];
        $guardian->additional_detail = $request['additional_detail'];
        // $guardian->status = $request->status;
        $guardian->save();
        return $guardian;
    }

    public function createRequest($request, $passenger_id)
    {
        $newRequest = new Requests();
        $newRequest->passenger_id = $passenger_id;
        $newRequest->organization_id = $request['organization_id'];
        $newRequest->guardian_code = substr(uniqid(), -8);
        $newRequest->student_id = isset($request['student_id']) ? $request['student_id'] : null;
        $newRequest->employee_id = isset($request['employee_id']) ? $request['employee_id'] : null;
        $newRequest->type = $request['type'];
        $newRequest->student_type = $request['student_type'];
        $newRequest->roll_no = $request['roll_no'];
        $newRequest->class = $request['class'];
        $newRequest->section = $request['section'];
        $newRequest->qualification = $request['qualification'];
        $newRequest->batch_year = $request['batch_year'];
        $newRequest->degree_duration = $request['degree_duration'];
        $newRequest->discipline = $request['discipline'];
        $newRequest->designation = $request['designation'];
        $newRequest->profile_card = $request['profile_card'];
        $newRequest->route_id = $request['route_id'];
        $newRequest->transport_start_date = $request['transport_start_date'];
        $newRequest->transport_end_date = $request['transport_end_date'];
        $newRequest->status = Requests::STATUS_PENDING;
        $newRequest->save();
        return $newRequest;
    }


    public function getRequestDetailByCode($code)
    {
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

    public function search(Request $request): JsonResponse
    {
        $manager = auth('manager')->user();

        $allRequestsQuery = Requests::with('organization:id,name')
            ->with('city:id,name')
            ->with('route:id,name')
            ->with('passenger:id,name,phone')
            ->where('organization_id', $manager->o_id)
            ->when($request->string, function ($query) use ($request) {
                return $query
                    ->where('name', 'like', '%' . $request->string . '%')
                    ->orWhere('phone', 'like', '%' . $request->string . '%')
                    ->orWhere('email', 'like', '%' . $request->string . '%')
                    ->orWhere('house_no', 'like', '%' . $request->string . '%')
                    ->orWhere('street_no', 'like', '%' . $request->string . '%')
                    ->orWhere('town', 'like', '%' . $request->string . '%')
                    ->orWhere('pickup_address', 'like', '%' . $request->string . '%')
                    ->orWhere('additional_detail', 'like', '%' . $request->string . '%')
                    ->orWhere('roll_no', 'like', '%' . $request->string . '%')
                    ->orWhere('class', 'like', '%' . $request->string . '%')
                    ->orWhere('section', 'like', '%' . $request->string . '%')
                    ->orWhere('qualification', 'like', '%' . $request->string . '%')
                    ->orWhere('batch_year', 'like', '%' . $request->string . '%')
                    ->orWhere('degree_duration', 'like', '%' . $request->string . '%')
                    ->orWhere('discipline', 'like', '%' . $request->string . '%')
                    ->orWhere('employee_comp_id', 'like', '%' . $request->string . '%')
                    ->orWhere('designation', 'like', '%' . $request->string . '%')
                    ->orWhere('profile_card', 'like', '%' . $request->string . '%')
                    ->orWhere('cnic_no', 'like', '%' . $request->string . '%')
                    ->orWhere('relation', 'like', '%' . $request->string . '%')
                    ->orWhere('guardian_code', 'like', '%' . $request->string . '%')
                    ->orWhere('transport_start_date', 'like', '%' . $request->string . '%')
                    ->orWhere('transport_end_date', 'like', '%' . $request->string . '%')
                    ->orWhere('status', 'like', '%' . $request->string . '%');
            });

        if ($request->has('status')) {
            if ($request->status === 'past') {
                $allRequests = $allRequestsQuery->onlyTrashed()->get();
            } else {
                $allRequests = $allRequestsQuery->where('status', $request->status)->get();
            }
        } else {
            $allRequests = $allRequestsQuery->get();
            $approvedRequests = $allRequests->where('status', Requests::STATUS_APPROVED)->values();
            $pendingRequests = $allRequests->where('status', Requests::STATUS_PENDING)->values();

            $response = [
                'approved_requests' => $approvedRequests,
                'pending_requests' => $pendingRequests,
            ];
            return $this->respondWithSuccess($response, 'list of users', 'FETCHED_REQUESTS_WITH_STATUS');
        }

        return $this->respondWithSuccess($allRequests, 'list of users', 'FETCHED_REQUESTS_WITH_STATUS');
    }
}
