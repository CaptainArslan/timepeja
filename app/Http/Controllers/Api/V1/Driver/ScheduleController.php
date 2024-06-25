<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Models\Organization;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\BaseController;
use App\Models\Driver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends BaseController
{
    // public function __construct()
    // {
    //     $this->middleware('auth:driver');
    // }

    public function index($date)
    {
        $validator = Validator::make(['date' => $date], [
            'date' => ['required', 'date'],
        ], [
            'date.date' => 'Date must be a valid date',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError(implode(",", $validator->errors()->all()));
        }

        try {
            $driver = auth('driver')->user();
            if ($driver->online_status == Driver::STATUS_OFFLINE) {
                return $this->respondWithError('Driver is offline. Please make yourself available to view schedules.');
            }

            $schedules = Schedule::where('d_id', $driver->id)
                ->where('date', $date)
                ->where('o_id', $driver->o_id)
                ->where('trip_status', Schedule::TRIP_STATUS_UPCOMING)
                // ->select('id', 'o_id', 'route_id', 'v_id', 'd_id', 'date', 'time', 'status', 'trip_status', 'created_at')
                // ->with('organizations:id,name')
                ->with('routes:id,name,number,from,to')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->get();

            return $this->respondWithSuccess($schedules, 'Schedules retrieved successfully.', 'DRIVER_SCHEDULES_RETRIEVED');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->respondWithError('Something went wrong.', $th->getMessage());
        }
    }

    public function schedules($date = null)
    {
        try {
            $date = $date ? $date : now()->format('Y-m-d');
            $driver = auth('driver')->user();

            if ($driver->online_status == Driver::STATUS_OFFLINE) {
                return $this->respondWithError('Driver is offline. Please make yourself available to view schedules.');
            }

            $schedules = Schedule::where('d_id', $driver->id)
                ->with('routes:id,name,number,from,to')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->when($date !== null, function ($query) use ($date) {
                    return $query->where('date', $date)
                        ->orWhere('time', '>=', now()->format('H:i:s'));
                })
                ->get();

            return $this->respondWithSuccess($schedules, 'Schedules retrieved successfully.', 'DRIVER_SCHEDULES_RETRIEVED');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->respondWithError('Something went wrong.', $th->getMessage());
        }
    }

    public function filterSchedules(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'start_date' => ['required', 'date'],
            'last_date' => ['required', 'date'],
        ], [
            'start_date.date' => 'Start date must be a valid date',
            'last_date.date' => 'Last date must be a valid date',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError(implode(",", $validator->errors()->all()));
        }

        try {
            $driver = auth('driver')->user();
            $schedules = Schedule::where('d_id', $driver->id)
                ->with('routes:id,name')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->where('date', '>=', $request->start_date)
                ->where('date', '<=', $request->last_date)
                // ->where('trip_status', Schedule::TRIP_STATUS_COMPLETED)
                ->get();
            $download_url = null;
            $arr = [
                'schedules' => $schedules,
                'download_url' => $download_url
            ];

            return $this->respondWithSuccess($arr, 'Schedules retrieved successfully.', 'DRIVER_SCHEDULES_RETRIEVED');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->respondWithError('Something went wrong.', $th->getMessage());
        }
    }


    public function online()
    {
        try {
            $driver = auth('driver')->user();
            $driver->online_status = Driver::STATUS_ONLINE;
            $driver->save();
            $organization = $driver->organization;
            if (!empty($organization)) {
                $manager = $organization->manager;
                $token = $manager ? $manager->device_token : null;
                if ($token) {
                    $time = now()->format('h:i:s A');
                    $message = "Driver {$driver->name} is online now at {$time}";
                    Log::info($message);
                    notification('Driver Online', $message, $token);
                }
            }

            return $this->respondWithSuccess(null, 'Driver is online now.', 'DRIVER_ONLINE');
        } catch (\Throwable $th) {
            return $this->respondWithError('Something went wrong.', $th->getMessage());
        }
    }

    public function offline()
    {
        try {
            $driver = auth('driver')->user();
            $driver->online_status = Driver::STATUS_OFFLINE;
            $driver->save();

            $organization = $driver->organization;
            if (!empty($organization)) {
                $manager = $organization->manager;
                $token = $manager ? $manager->device_token : null;
                if ($token) {
                    $time = now()->format('h:i:s A');
                    $message = "Driver {$driver->name} is offline now at {$time}";
                    Log::info($message);
                    notification('Driver Offline', $message, $token);
                }
            }
            return $this->respondWithSuccess(null, 'Driver is offline now.', 'DRIVER_OFFLINE');
        } catch (\Throwable $th) {
            return $this->respondWithError('Something went wrong.', $th->getMessage());
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function startTrip($id): JsonResponse
    {
        $validator = Validator::make(['schedule_id' => $id], [
            'schedule_id' => ['required', 'integer'],
        ], [
            'schedule_id.required' => 'Schedule id is required',
            'schedule_id.integer' => 'Schedule id must be integer',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $schedule = Schedule::where('id', $id)
                ->with('drivers:id,name,license_no')
                ->with('organizations:id,name,address')
                ->with('routes:id,name')
                ->with('vehicles:id,number')
                ->first();

            if (!$schedule) {
                return $this->respondWithError('Schedule not found.');
            }
            // Get the current time
            $currentTime = now();

            // Compare the current time with the actual start time
            if ($currentTime->greaterThan($schedule->start_time)) {
                // If the current time is greater, set the is_delay column to 1
                $schedule->is_delay = Schedule::TRIP_ISDELAYED;
            }
            $schedule->trip_status = Schedule::TRIP_STATUS_INPROGRESS;
            $schedule->save();

            $organization = Organization::where('id', $schedule->o_id)->first();
            if ($organization) {
                Log::info('Trip started');

                Log::info('Organization found');
                $manager = $organization->manager;
                Log::info('Manager found');
                $token = $manager ? $manager->device_token : null;
                Log::info('Token found');
                $route = Route::where('id', $schedule->route_id)->first();
                $driver = Driver::where('id', $schedule->d_id)->first();
                Log::info('Route and Driver found');
                $time = now()->format('h:i:s A');
                $message = "Driver {$driver->name} started his trip of route name {$route->name} at {$time}" ;
                if ($token) {
                    notification('Trip Started', $message, $token);
                }
            }

            return $this->respondWithSuccess($schedule, 'Trip started successfully.', 'DRIVER_TRIP_STARTED');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->respondWithError('Something went wrong.', $th->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function endTrip(Request $request, $id): JsonResponse
    {
        $validator = Validator::make(['schedule_id' => $id], [
            'schedule_id' => ['required', 'integer'],
        ], [
            'schedule_id.required' => 'Schedule id is required',
            'schedule_id.integer' => 'Schedule id must be integer',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $schedule = Schedule::find($id);
            if (!$schedule) {
                return $this->respondWithError('Schedule not found.');
            }

            $schedule->trip_status = Schedule::TRIP_STATUS_COMPLETED;
            $schedule->end_time = date("h:i:s");
            $schedule->save();

            $organization = Organization::where('id', $schedule->o_id)->first();
            if ($organization) {
                Log::info('Trip Eneded');

                Log::info('Organization found');
                $manager = $organization->manager;
                Log::info('Manager found');
                $token = $manager ? $manager->device_token : null;
                Log::info('Token found');
                $route = Route::where('id', $schedule->route_id)->first();
                $driver = Driver::where('id', $schedule->d_id)->first();
                Log::info('Route and Driver found');
                $time = now()->format('h:i:s A');
                $message = "Driver {$driver->name} Ended his trip of route name {$route->name} at {$time}" ;
                if ($token) {
                    notification('Trip Ended', $message, $token);
                }
            }

            return $this->respondWithSuccess(null, 'Trip completed successfully.', 'DRIVER_TRIP_STARTED');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->respondWithError('Something went wrong.', $th->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function delayTrip(Request $request, $id): JsonResponse
    {
        $validator = Validator::make(['schedule_id' => $id,], [
            'schedule_id' => ['required', 'integer'],
        ], [
            'schedule_id.required' => 'Schedule id is required',
            'schedule_id.integer' => 'Schedule id must be integer',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $schedule = Schedule::find($id);
            if (!$schedule) {
                return $this->respondWithError('Schedule not found.');
            }
            $schedule->is_delay = Schedule::TRIP_ISDELAYED;
            $schedule->delayed_reason = $request->delayed_reason;
            $schedule->trip_status = Schedule::TRIP_STATUS_DELAYED;
            $schedule->save();
            $organization = $schedule->organization;

            $organization = Organization::where('id', $schedule->o_id)->first();
            if ($organization) {
                Log::info('Trip Delayed');
                Log::info('Organization found');
                $manager = $organization->manager;
                Log::info('Manager found');
                $token = $manager ? $manager->device_token : null;
                Log::info('Token found');
                $route = Route::where('id', $schedule->route_id)->first();
                $driver = Driver::where('id', $schedule->d_id)->first();
                Log::info('Route and Driver found');
                $message = "Trip toward the route name {$route->name} taken by Driver {$driver->name} has been delayed";
                if ($token) {
                    notification('Trip Started', $message, $token);
                }
            }
            return $this->respondWithSuccess($schedule, 'Trip started successfully.', 'DRIVER_TRIP_STARTED');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->respondWithError('Something went wrong.', $th->getMessage());
        }
    }

    /**
     * @return JsonResponse
     */
    public function notifications(): JsonResponse
    {
        try {
            $driver = auth('driver')->user();
            $schedules_notifications = Schedule::where('d_id', $driver->id)
                ->where('status', Schedule::STATUS_PUBLISHED)
                ->where('date', now()->format('Y-m-d'))
                ->where('time', '>=', now()->format('H:i:s'))
                ->where('time', '<=', now()->addMinutes(15)->format('H:i:s'))
                ->with('routes:id,name')
                ->with('vehicles:id,number')
                ->first();
            return $this->respondWithSuccess($schedules_notifications, 'Notifications retrieved successfully.', 'DRIVER_NOTIFICATIONS_RETRIEVED');
        } catch (\Throwable $th) {
            return $this->respondWithError('Something went wrong.', $th->getMessage());
        }
    }
}
