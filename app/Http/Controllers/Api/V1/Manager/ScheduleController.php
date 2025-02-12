<?php

namespace App\Http\Controllers\Api\V1\Manager;

use App\Models\Route;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\FcmNotificationEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Manager\Schedule\StoreScheduleRequest;
use App\Http\Requests\Manager\Schedule\UpdateScheduleRequest;
use App\Http\Requests\Manager\Schedule\PublishScheduleRequest;

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

    public function show($id): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $schedule = Schedule::findOrFail($id);

        if (!$schedule) {
            return $this->respondWithError('Schedule not found');
        }

        if ($schedule->organization_id !== $manager->organization_id) {
            return $this->respondWithError('Unauthorized...');
        }

        $schedule->load([
            'organization',
            'route',
            'vehicle',
            'driver'
        ]);

        return $this->respondWithSuccess($schedule, 'Get schedule', 'API_GET_SCHEDULE');
    }

    public function update(UpdateScheduleRequest $request, $id): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $schedule = Schedule::findOrFail($id);

        if (!$schedule) {
            return $this->respondWithError('Schedule not found');
        }

        if ($schedule->organization_id !== $manager->organization_id) {
            return $this->respondWithError('You are not authorized to update this schedule');
        }

        if ($schedule->status === Schedule::STATUS_PUBLISHED) {
            return $this->respondWithError('Published schedule can not be updated');
        }

        $schedule->update([
            'route_id' => $request->route_id,
            'vehicle_id' => $request->vehicle_id,
            'driver_id' => $request->driver_id,
            'date' => $request->date,
            'time' => $request->time,
        ]);

        $schedule->load([
            'organization',
            'route',
            'vehicle',
            'driver'
        ]);

        return $this->respondWithSuccess($schedule, 'Schedule updated successfully', 'SCHEDULE_UPDATED');
    }

    public function destroy($id): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $schedule = Schedule::findOrFail($id);

        if (!$schedule) {
            return $this->respondWithError('Schedule not found');
        }

        if ($schedule->organization_id !== $manager->organization_id) {
            return $this->respondWithError('You are not authorized to delete this schedule');
        }

        $schedule->delete();
        return $this->respondWithDelete('Schedule deleted successfully', 'API_SCHEDULE_DELETED');
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
            ->with([
                'route:id,name',
                'vehicle:id,number',
                'driver:id,name',
                'organization:id,name'
            ])
            ->get()
            ->each(function ($schedule) use (&$publishedSchedules, &$draftSchedules) {
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

    public function getSchedulesbyDate($status, $date): JsonResponse
    {
        $validator = Validator::make([
            'date' => $date,
            'status' => $status
        ], [
            'date' => ['required', 'date', 'date_format:Y-m-d'],
            'status' => ['required', 'in:published,draft']
        ], [
            'date.required' => 'Date is required',
            'date.date' => 'Invalid date format',
            'status.required' => 'Status is required',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $schedules = Schedule::byOrganization($manager->o_id)
            ->with([
                'route:id,name',
                'vehicle:id,number',
                'driver:id,name',
                'organization:id,name'
            ])
            ->where('date', $date)
            ->where('status', $status)
            ->get();

        return $this->respondWithSuccess($schedules, 'Schedule by date', 'SCHEDULES_BY_DATE');
    }

    public function publish(PublishScheduleRequest $request): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $scheduleIds = (array) $request->schedule_ids;

        try {
            $driverIds = [];
            $schedules = Schedule::whereIn('id', $scheduleIds)->get();

            if ($schedules->isEmpty()) {
                return $this->respondWithError('No schedules found for the given IDs');
            }

            // Extract date from first schedule (assuming all schedules are for the same day)
            $date = $schedules->first()->date;

            DB::transaction(function () use ($schedules, &$driverIds) {
                foreach ($schedules as $schedule) {
                    $driverIds[] = $schedule->d_id;
                    $schedule->update(['status' => Schedule::STATUS_PUBLISHED]);
                }
            });

            try {
                $deviceTokens = Driver::whereIn('id', $driverIds)
                    ->with('deviceTokens')  // Eager load the polymorphic relationship
                    ->get()
                    ->pluck('deviceTokens.*.token')  // Pluck the 'token' field from the deviceTokens relation
                    ->flatten()  // Flatten the array to get a single list of tokens
                    ->unique()  // Get unique tokens
                    ->toArray();  // Convert the result to an array

                FcmNotificationEvent::dispatch($deviceTokens, 'Schedule Published', 'New schedule has been published!', [
                    'type' => 'SCHEDULE_PUBLISHED',
                    'date' => $date,
                ]);
            } catch (\Throwable $th) {
                Log::error('Error occurred while sending notification: ' . $th->getMessage());
            }

            return $this->respondWithSuccess(null, 'Schedules published successfully', 'PUBLISH_SCHEDULE');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error occurred while publishing schedules: ' . $th->getMessage());
        }
    }
    public function draft(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'Schedule_ids' => ['required'],
            'Schedule_ids.*' => ['integer'],
        ], [
            'Schedule_ids.required' => 'Schedule ids are required',
            'Schedule_ids.*.integer' => 'ID must be an integer',
            'Schedule_ids.*.exists' => 'Invalid ID provided',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        $ScheduleIds = (array) $request->Schedule_ids;

        try {

            DB::transaction(function () use ($ScheduleIds, &$error) {
                foreach ($ScheduleIds as $id) {
                    $schedule = Schedule::findOrFail($id);
                    $schedule->status = Schedule::STATUS_DRAFT;
                    if (!$schedule->save()) {
                        return $this->respondWithError('Error Occured while drafting schedule');
                    }
                }
            });

            return $this->respondWithSuccess(null, 'Schedules draft successfully', 'DRAFT_SCHEDULE');
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
