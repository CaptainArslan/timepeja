<?php

namespace App\Http\Controllers\Api\V1\Passenger;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Request as ModelsRequest;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends BaseController
{
    public function index(Request $request, $id)
    {
        try {
            $date = $request->date ?? date('Y-m-d');
            $schedule = Schedule::where('o_id', $id)
                ->where('date', $date)
                ->where('status', Schedule::STATUS_PUBLISHED)
                ->with('routes:id,name,number,from,to')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->with('organizations:id,name')
                ->select('id', 'o_id', 'route_id', 'v_id', 'd_id', 'date', 'time', 'status', 'trip_status')
                ->get();

            dd($schedule->toArray());

            $data = [
                'transport_Schedule' => $schedule,
                'transport_routes' => $this->getRoutes()
            ];
            return $this->respondWithSuccess($data, 'Published schedules retrieved successfully.', 'ORGANIZATION_SCHEDULE_FETCHED_SUCCESSFULLY');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occurred while fetching schedule!');
        }
    }

    public function getRoutes()
    {
        try {
            $passenger = auth('passenger')->user();
            $data = ModelsRequest::where('passenger_id', $passenger->id)->where('status', ModelsRequest::STATUS_APPROVED)->pluck('organization_id');
            $unique_organization_ids = $data->unique()->values()->toArray();
            $routes = Route::whereIn('o_id', $unique_organization_ids)->where('status', Route::STATUS_ACTIVE)->select('id', 'name', 'number', 'from', 'to', 'o_id')->with('organization:id,name')->get();
            return $routes;
        } catch (\Throwable $th) {
            return [];
        }
    }
    // public function getRoutes()
    // {
    //     try {
    //         $passenger = auth('passenger')->user();

    //         $routes = ModelsRequest::where('passenger_id', $passenger->id)
    //             ->where('status', ModelsRequest::STATUS_APPROVED)
    //             ->select('organization_id')
    //             ->distinct()
    //             ->pluck('organization_id');

    //         $routeDetails = Route::whereIn('o_id', $routes)
    //             ->where('status', Route::STATUS_ACTIVE)
    //             ->select('id', 'name', 'number', 'from', 'to', 'o_id')
    //             ->get();

    //         // Group routes by organization_id
    //         $groupedRoutes = [];

    //         foreach ($routeDetails as $route) {
    //             $organizationId = $route->organization?->name;

    //             if (!isset($groupedRoutes[$organizationId])) {
    //                 $groupedRoutes[$organizationId] = [];
    //             }

    //             $groupedRoutes[$organizationId][] = $route;
    //         }

    //         return $groupedRoutes;
    //     } catch (\Throwable $th) {
    //         return $this->respondWithError('Error Occurred while fetching routes: ' . $th->getMessage());
    //     }
    // }
}
