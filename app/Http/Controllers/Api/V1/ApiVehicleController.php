<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiVehicleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        try {
            $manager = auth('manager')->user();
            $vehicles = Vehicle::where('o_id', $manager->o_id)
                ->select('id', 'o_id', 'v_type_id', 'number', 'front_pic', 'number_pic', 'status', 'created_at', 'updated_at')
                // ->with('organizations', function ($query) {
                //     $query->select('id', 'name', 'branch_name', 'address', 'phone', 'email');
                // })
                ->with('vehiclesTypes', function ($query) {
                    $query->select('id', 'name', 'desc');
                })->where('status', Vehicle::STATUS_ACTIVE)
                ->paginate(Vehicle::VEHICLE_LIMIT_PER_PAGE);
            // if ($vehicles->isEmpty()) {
            //     return $this->respondWithError('No data found');
            // }
            return $this->respondWithSuccess($vehicles, 'Oganization All Vehicle', 'ORGANIZATION_VEHICLE');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while fetching organization driver');
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): JsonResponse
    {
        return $this->respondWithSuccess(null, 'Vehicle create form', 'VEHICLE_CREATE_FORM');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'v_type_id' => ['required', 'numeric', 'exists:vehicle_types,id'],
            'number' => ['required', 'string', 'unique:vehicles,number'],
            'front_pic' => [
                'required',
                'string'
                // 'image',
                // 'mimes:jpeg,png,jpg,gif,svg',
                // 'max:2048'
            ],
            'number_pic' => [
                'required',
                'string'
                // 'image',
                // 'mimes:jpeg,png,jpg,gif,svg',
                // 'max:2048'
            ],
        ], [
            'o_id.required' => 'Organization required',
            'v_type_id.required' => 'Vehicle type id required',
            'v_type_id.exists' => 'Invalid vehicle type id',
            'number.required' => 'Vehicle number required',
            'number.unique' => 'Vehicle number already exists',
            'front_pic.required' => 'Vehicle front pic required',
            'front_pic.image' => 'Vehicle front pic must be an image',
            'front_pic.mimes' => 'Vehicle front pic must be a file of type: jpeg, png, jpg, gif, svg',
            'front_pic.max' => 'Vehicle front pic may not be greater than 2048 kilobytes',
            'number_pic.required' => 'Vehicle number plate pic required',
            'number_pic.image' => 'Vehicle number plate pic must be an image',
            'number_pic.mimes' => 'Vehicle number plate pic must be a file of type: jpeg, png, jpg, gif, svg',
            'number_pic.max' => 'Vehicle number plate pic may not be greater than 2048 kilobytes',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError(implode(',', $validator->errors()->all()));
        }

        try {
            $manager = auth('manager')->user();
            $vehicle = new Vehicle();
            $vehicle->u_id  = $manager->id;
            $vehicle->o_id   = $manager->o_id;
            $vehicle->v_type_id    = $request->v_type_id;
            $vehicle->number   = $request->number;
            $vehicle->front_pic   = $request->front_pic;
            $vehicle->number_pic   = $request->number_pic;
            $vehicle->status = Vehicle::STATUS_ACTIVE;

            if ($vehicle->save()) {
                return $this->respondWithSuccess($vehicle, 'Vehicle created successfully', 'VEHICLE_CREATED');
            } else {
                return $this->respondWithError('Error occured while creating vehicle');
            }
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while vehicle creation');
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['exists:vehicles,id', 'required']
        ], [
            'id.exists' => 'Vehicle id not found',
            'id.required' => 'vehicle id is required'
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }
        try {
            // $manager = auth('manager')->user();
            $vehicle = Vehicle::findOrFail($id)
                ->select('id', 'o_id', 'v_type_id', 'number', 'front_pic', 'number_pic', 'status')
                ->where('status', Vehicle::STATUS_ACTIVE)
                ->with('vehiclesTypes', function ($query) {
                    $query->select('id', 'name', 'desc');
                })
                ->first();

            return $this->respondWithSuccess($vehicle, 'Get Vehcile', 'API_GET_VEHICLE');
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Vehicle id not found');
            // throw new NotFoundHttpException('Vehicle id not found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): JsonResponse
    {
        return $this->respondWithSuccess(null, 'Vehicle edit form', 'VEHICLE_EDIT_FORM');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'v_type_id' => ['required', 'numeric', 'exists:vehicle_types,id'],
            'number' => ['required', 'string', Rule::unique('vehicles')->ignore($id)],
            'front_pic' => [
                'required',
                'string'
                // 'image',
                // 'mimes:jpeg,png,jpg,gif,svg',
                // 'max:2048'
            ],
            'number_pic' => [
                'required',
                'string'
                // 'image',
                // 'mimes:jpeg,png,jpg,gif,svg',
                // 'max:2048'
            ],
        ], [
            'o_id.required' => 'Organization required',
            'v_type_id.required' => 'Vehicle type id required',
            'v_type_id.exists' => 'Invalid vehicle type id',
            'number.required' => 'Vehicle number required',
            'number.unique' => 'Vehicle number already exists',
            'front_pic.required' => 'Vehicle front pic required',
            'front_pic.image' => 'Vehicle front pic must be an image',
            'front_pic.mimes' => 'Vehicle front pic must be a file of type: jpeg, png, jpg, gif, svg',
            'front_pic.max' => 'Vehicle front pic may not be greater than 2048 kilobytes',
            'number_pic.required' => 'Vehicle number plate pic required',
            'number_pic.image' => 'Vehicle number plate pic must be an image',
            'number_pic.mimes' => 'Vehicle number plate pic must be a file of type: jpeg, png, jpg, gif, svg',
            'number_pic.max' => 'Vehicle number plate pic may not be greater than 2048 kilobytes',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $vehicle = Vehicle::findorFail($id);

            if ($request->has('front_pic') && $vehicle->front_pic_name != null) {
                removeImage($vehicle->front_pic_name, 'vehicles');
            }

            if ($request->has('number_pic') && $vehicle->number_pic_name != null) {
                removeImage($vehicle->number_pic_name, 'vehicles');
            }

            $vehicle->v_type_id    = $request->v_type_id;
            $vehicle->number   = $request->number;
            $vehicle->front_pic   = ($request->has('front_pic')) ? $request->front_pic : $vehicle->front_pic_name;
            $vehicle->number_pic   = ($request->has('number_pic')) ? $request->number_pic : $vehicle->number_pic_name;

            if ($vehicle->save()) {
                return $this->respondWithSuccess($vehicle, 'Vehicle updated successfully', 'VEHICLE_UPDATED');
            } else {
                return $this->respondWithError('Error occured while updating vehicle');
            }
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('Vehicle id not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'int', 'exists:vehicles,id']
        ], [
            'id.exists' => 'Invalid vehicle id',
            'id.required' => 'vehicle id is required'
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $vehicle = Vehicle::findOrFail($id);
            $vehicle->delete();
            return $this->respondWithDelete('Vehicle deleted successfully', 'API_VEHICLE_DELETED');
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Vehicle id not found');
            throw new NotFoundHttpException('Vehicle id not found' . $e->getMessage());
        }
    }

    /**
     * Search vehicle
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(): JsonResponse
    {
        try {
            $string = request()->input('string');
            $manager = auth('manager')->user();
            $vehicles = Vehicle::where('number', 'LIKE', '%' . $string . '%')
                ->orWhere('o_id', 'LIKE', '%' . $string . '%')
                ->orWhere('v_type_id', 'LIKE', '%' . $string . '%')
                ->orWhere('status', 'LIKE', '%' . $string . '%')
                ->orWhere('created_at', 'LIKE', '%' . $string . '%')
                ->orWhere('updated_at', 'LIKE', '%' . $string . '%')
                ->where('o_id', $manager->o_id)
                ->select('id', 'number')
                ->get();
            if ($vehicles->isEmpty()) {
                return $this->respondWithError('No Vehicle found');
            }
            return $this->respondWithSuccess($vehicles, 'Vehicle retrieved successfully', 'API_VEHICLE_SEARCH_RESULT');
        } catch (ModelNotFoundException $th) {
            throw new NotFoundHttpException('Error occured while fetching data');
        }
    }

    /**
     * Get vehicle types
     *
     * @return \Illuminate\Http\Response
     */
    public function getVehicle(): JsonResponse
    {
        try {
            $manager = auth('manager')->user();
            $vehicles = Vehicle::where('o_id', $manager->o_id)
                ->select('id', 'o_id', 'v_type_id', 'number', 'front_pic', 'number_pic', 'status', 'created_at', 'updated_at')
                ->with('vehiclesTypes', function ($query) {
                    $query->select('id', 'name as vehicle_name');
                })
                ->where('status', Vehicle::STATUS_ACTIVE)
                ->get();
            return $this->respondWithSuccess($vehicles, 'Oganization All Vehicle', 'ORGANIZATION_VEHICLE');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while fetching organization driver');
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWeb(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'v_type_id' => ['required', 'numeric', 'exists:vehicle_types,id'],
            'number' => ['required', 'string', 'unique:vehicles,number'],
            'front_pic' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048'
            ],
            'number_pic' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048'
            ],
        ], [
            'o_id.required' => 'Organization required',
            'v_type_id.required' => 'Vehicle type id required',
            'v_type_id.exists' => 'Invalid vehicle type id',
            'number.required' => 'Vehicle number required',
            'number.unique' => 'Vehicle number already exists',
            'front_pic.required' => 'Vehicle front pic required',
            'front_pic.image' => 'Vehicle front pic must be an image',
            'front_pic.mimes' => 'Vehicle front pic must be a file of type: jpeg, png, jpg, gif, svg',
            'front_pic.max' => 'Vehicle front pic may not be greater than 2048 kilobytes',
            'number_pic.required' => 'Vehicle number plate pic required',
            'number_pic.image' => 'Vehicle number plate pic must be an image',
            'number_pic.mimes' => 'Vehicle number plate pic must be a file of type: jpeg, png, jpg, gif, svg',
            'number_pic.max' => 'Vehicle number plate pic may not be greater than 2048 kilobytes',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError(implode(',', $validator->errors()->all()));
        }

        try {
            $manager = auth('manager')->user();
            $vehicle = new Vehicle();
            $vehicle->u_id  = $manager->id;
            $vehicle->o_id   = $manager->o_id;
            $vehicle->v_type_id    = $request->v_type_id;
            $vehicle->number   = $request->number;
            $vehicle->front_pic   =  $request->file('front_pic') ? uploadImage($request->file('front_pic'), 'vehicles', 'vehicle') : null;
            // $request->front_pic;
            $vehicle->number_pic   = $request->file('number_pic') ? uploadImage($request->file('number_pic'), 'vehicles', 'vehicle') : null;
            $vehicle->status = Vehicle::STATUS_ACTIVE;

            if ($vehicle->save()) {
                return $this->respondWithSuccess($vehicle, 'Vehicle created successfully', 'VEHICLE_CREATED');
            } else {
                return $this->respondWithError('Error occured while creating vehicle');
            }
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while vehicle creation');
            // throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateWeb(Request $request, $id): JsonResponse
    {
        try {
            $vehicle = Vehicle::findorFail($id);

            $validator = Validator::make($request->all(), [
                'v_type_id' => ['required', 'numeric', 'exists:vehicle_types,id'],
                'number' => ['required', 'string', 'unique:vehicles,number,' . $id],
                'front_pic' => [
                    // 'required',
                    'image',
                    'mimes:jpeg,png,jpg,gif,svg',
                    'max:2048'
                ],
                'number_pic' => [
                    // 'required',
                    'image',
                    'mimes:jpeg,png,jpg,gif,svg',
                    'max:2048'
                ],
            ], [
                'o_id.required' => 'Organization required',
                'v_type_id.required' => 'Vehicle type id required',
                'v_type_id.exists' => 'Invalid vehicle type id',
                'number.required' => 'Vehicle number required',
                'number.unique' => 'Vehicle number already exists',
                'front_pic.required' => 'Vehicle front pic required',
                'front_pic.image' => 'Vehicle front pic must be an image',
                'front_pic.mimes' => 'Vehicle front pic must be a file of type: jpeg, png, jpg, gif, svg',
                'front_pic.max' => 'Vehicle front pic may not be greater than 2048 kilobytes',
                'number_pic.required' => 'Vehicle number plate pic required',
                'number_pic.image' => 'Vehicle number plate pic must be an image',
                'number_pic.mimes' => 'Vehicle number plate pic must be a file of type: jpeg, png, jpg, gif, svg',
                'number_pic.max' => 'Vehicle number plate pic may not be greater than 2048 kilobytes',
            ]);

            if ($validator->fails()) {
                return $this->respondWithError($validator->errors()->first());
            }

            if ($request->has('front_pic') && $vehicle->front_pic_name != null) {
                removeImage($vehicle->front_pic_name, 'vehicles', 'vehicle');
            }

            if ($request->has('number_pic') && $vehicle->number_pic_name != null) {
                removeImage($vehicle->number_pic_name, 'vehicles', 'vehicle');
            }

            $vehicle->v_type_id    = $request->v_type_id;
            $vehicle->number   = $request->number;
            $vehicle->front_pic   = ($request->has('front_pic')) ? uploadImage($request->file('front_pic'), 'vehicles', 'vehicle') : $vehicle->front_pic_name;
            $vehicle->number_pic   = ($request->has('number_pic')) ? uploadImage($request->file('number_pic'), 'vehicles', 'vehicle') : $vehicle->number_pic_name;

            if ($vehicle->save()) {
                return $this->respondWithSuccess($vehicle, 'Vehicle updated successfully', 'VEHICLE_UPDATED');
            } else {
                return $this->respondWithError('Error occured while updating vehicle');
            }
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('Vehicle not found');
        }
    }
}
