<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Route;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiManagerController extends BaseController
{
    public function wrapper(): jsonResponse
    {
        try {
            $manager = auth('manager')->user();
            $data = [];
            $routes = Route::where('o_id', $manager->o_id)
                ->where('status', Route::STATUS_ACTIVE)
                ->select('id', 'name')
                ->get();

            $vehicles = Vehicle::where('o_id', $manager->o_id)
                ->where('status', Vehicle::STATUS_ACTIVE)
                ->select('id', 'number')
                ->get();

            $drivers = Driver::where('o_id', $manager->o_id)
                ->where('status', Driver::STATUS_ACTIVE)
                ->select('id', 'name')
                ->get();

            $data = [
                'routes' => $routes,
                'vehicles' => $vehicles,
                'drivers' => $drivers,
            ];

            // store data in cache
            Cache::put('SCREEN_WRAPPER_' . $manager->o_id, $data, now()->addDay(1));
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('Data not found ' . $e->getMessage());
        }

        return $this->respondWithSuccess(
            $data,
            'Organization route, vehicle, driver data',
            'ORGANIZATION_ROUTE_VEHICLE_DRIVER_DATA'
        );
    }
}
