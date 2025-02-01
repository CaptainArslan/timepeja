<?php

namespace App\Http\Controllers\Api\V1;

use PDF;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Models\Pdf as ModelsPdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ApiVehicleController extends BaseController
{
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
