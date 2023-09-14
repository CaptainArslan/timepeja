<?php

namespace App\Http\Controllers\Api\V1\Passenger;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends BaseController
{
    public function index($id, $date)
    {
        try {
            $data = Schedule::where('o_id', $id)
                ->where('date', $date)
                ->where('status', Schedule::STATUS_PUBLISHED)
                ->with('routes:id,name,number,from,to')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->with('organizations:id,name')
                ->select('id', 'o_id', 'route_id', 'v_id', 'd_id', 'date', 'time', 'status')
                ->get();
            return $this->respondWithSuccess($data, 'Published schedules retrieved successfully.', 'ORGANIZATION_SCHEDULE_FETCHED_SUCCESSFULLY');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occurred while fetching schedule: ' . $th->getMessage());
        }
    }
}
