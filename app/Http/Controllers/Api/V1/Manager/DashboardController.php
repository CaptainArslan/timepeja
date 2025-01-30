<?php

namespace App\Http\Controllers\Api\V1\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request): jsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found', 'MANAGER_NOT_FOUND');
        }

        $routes = Route::where('organization_id', $manager->organization_id)
            ->select('id', 'name')
            ->when(($request->type == 'routes'), function ($query) use ($request) {
                return $query->search($request->search);
            })
            ->latest()
            ->get();

        $vehicles = Vehicle::where('organization_id', $manager->organization_id)
            ->select('id', 'number')
            ->when(($request->type == 'vehicles'), function ($query) use ($request) {
                return $query->search($request->search);
            })
            ->get();

        $drivers = Driver::where('organization_id', $manager->organization_id)
            ->select('id', 'name')
            ->when(($request->type == 'drivers'), function ($query) use ($request) {
                return $query->search($request->search);
            })
            ->get();

        $data = [
            'routes' => $routes,
            'vehicles' => $vehicles,
            'drivers' => $drivers,
        ];

        return $this->respondWithSuccess(
            $data,
            'Organization route, vehicle, driver data',
            'ORGANIZATION_ROUTE_VEHICLE_DRIVER_DATA'
        );
    }
}
