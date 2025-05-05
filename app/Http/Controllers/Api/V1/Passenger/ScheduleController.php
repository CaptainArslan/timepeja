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
    public function index(Request $request, $organizationId)
    {
        $passenger = auth('passenger')->user();
        if (!$passenger) {
            return $this->respondWithError('Unauthorized', 'UNAUTHORIZED');
        }
        $organization = Organization::where('id', $organizationId)->first();
        if (!$organization) {
            return $this->respondWithError('Organization not found', 'ORGANIZATION_NOT_FOUND');
        }

        $date = $request->date ?? date('Y-m-d');
        $schedule = Schedule::byOrganization($organizationId)
            ->where('date', $date)
            ->where('status', Schedule::STATUS_PUBLISHED)
            ->with([
                'route',
                'vehicle',
                'driver',
                'organization'
            ])
            ->get();

        $data = [
            'transport_Schedule' => $schedule,
            'transport_routes' => $this->getRoutes()
        ];
        return $this->respondWithSuccess($data, 'Published schedules retrieved successfully.', 'ORGANIZATION_SCHEDULE_FETCHED_SUCCESSFULLY');
    }

    public function getRoutes()
    {
        $passenger = auth('passenger')->user();
        if (!$passenger) {
            return $this->respondWithError('Unauthorized', 'UNAUTHORIZED');
        }

        $data = ModelsRequest::where('passenger_id', $passenger->id)->where('status', ModelsRequest::STATUS_APPROVED)->pluck('organization_id');

        $unique_organization_ids = $data->unique()->values()->toArray();
        return Route::byOrganizationIds($unique_organization_ids)
            ->where('status', Route::STATUS_ACTIVE)
            ->with('organization:id,name')
            ->get();
    }
}
