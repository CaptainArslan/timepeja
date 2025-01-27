<?php

namespace App\Http\Controllers\Api\V1\Manager;

use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class VehicletypeController extends controller
{
    public function index(): JsonResponse
    {
        try {
            $data = VehicleType::where('status', VehicleType::STATUS_ACTIVE)
                ->select('id', 'name')
                ->get();
            return $this->respondWithSuccess($data, 'All Vehicle Types', 'API_VEHICLE_TYPE');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while fetching organization driver');
        }
    }
}
