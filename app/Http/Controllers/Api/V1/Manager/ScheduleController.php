<?php

namespace App\Http\Controllers\Api\V1\Manager;

use App\Models\Route;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Manager\Schedule\StoreScheduleRequest;

class ScheduleController extends Controller
{
    public function index(): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $schedule = $manager->organization->schedules()
            ->with([
                'organization',
                'route',
                'vehicle',
                'driver'
            ])
            ->get();

        return $this->respondWithSuccess($schedule, 'Oganization All Schedule', 'ORGANIZATION_SCHEDULE');
    }

    public function store(StoreScheduleRequest $request): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $schedule = Schedule::create([
            'organization_id' => $manager->organization_id,
            'route_id' => $request->route_id,
            'vehicle_id' => $request->vehicle_id,
            'driver_id' => $request->driver_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => Schedule::STATUS_DRAFT,
        ]);

        $data = $schedule->load([
            'organization:id,name',
            'route:id,name',
            'vehicle:id,number,vehicle_type_id',
            'vehicle.vehicleType:id,name',
            'driver:id,name'
        ]);

        return $this->respondWithSuccess($data, 'Schedule Created Successfully', 'SCHEDULE_CREATED');
    }


    public function getOrganizationData(Request $request): JsonResponse
    {
        $manager =  Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $organization = $manager->organization;
        $date = $request->date ?? date('Y-m-d');
        $publishedSchedules = [];
        $draftSchedules = [];
        $data = [];

        $routes = $organization->routes()
            ->where('status', Route::STATUS_ACTIVE)
            ->select('id', 'name')
            ->get();

        $vehicles = $organization->vehicles()
            ->where('status', Vehicle::STATUS_ACTIVE)
            ->select('id', 'number as  name')
            ->get();

        $drivers = $organization->drivers()
            ->where('status', Driver::STATUS_ACTIVE)
            ->select('id', 'name')
            ->get();

        $organization->schedules()
            ->where('date', $date)
            ->with(['route', 'vehicle', 'driver'])
            ->get()->map(function ($schedule) {
                if ($schedule->status == Schedule::STATUS_PUBLISHED) {
                    $publishedSchedules[] = $schedule;
                } else {
                    $draftSchedules[] = $schedule;
                }
            });

        $data = [
            'routes' => $routes,
            'vehicles' => $vehicles,
            'drivers' => $drivers,
            'published_schedule' => $publishedSchedules,
            'created_schedule' => $draftSchedules
        ];

        return $this->respondWithSuccess(
            $data,
            'Organization route, vehicle, driver data, published and created schedule',
            'ORGANIZATION_ROUTE_VEHICLE_DRIVER_DATA_SCHEDULE'
        );
    }
}
