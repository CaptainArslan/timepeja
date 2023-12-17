<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\BaseController;
use App\Models\Driver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:driver');
    }

    public function index($date)
    {
        $validator = Validator::make(['date' => $date], [
            'date' => ['nullable', 'date'],
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
                // ->select('id', 'o_id', 'route_id', 'v_id', 'd_id', 'date', 'time', 'status', 'trip_status', 'created_at')
                ->with('organizations:id,name')
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

    public function online()
    {
        try {
            $driver = auth('driver')->user();
            $driver->online_status = Driver::STATUS_ONLINE;
            $driver->save();
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
            return $this->respondWithSuccess(null, 'Driver is offline now.', 'DRIVER_OFFLINE');
        } catch (\Throwable $th) {
            return $this->respondWithError('Something went wrong.', $th->getMessage());
        }
    }

    public function startTrip($id)
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
            // Get the current time
            $currentTime = now();

            // Compare the current time with the actual start time
            if ($currentTime->greaterThan($schedule->start_time)) {
                // If the current time is greater, set the is_delay column to 1
                $schedule->is_delay = 1;
            }
            $schedule->trip_status = Schedule::TRIP_STATUS_INPROGRESS;
            $schedule->save();
            return $this->respondWithSuccess($schedule, 'Trip started successfully.', 'DRIVER_TRIP_STARTED');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->respondWithError('Something went wrong.', $th->getMessage());
        }
    }

    public function endTrip(Request $request, $id)
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
            return $this->respondWithSuccess($schedule, 'Trip started successfully.', 'DRIVER_TRIP_STARTED');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->respondWithError('Something went wrong.', $th->getMessage());
        }
    }

    public function delayTrip(Request $request, $id)
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
            return $this->respondWithSuccess($schedule, 'Trip started successfully.', 'DRIVER_TRIP_STARTED');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->respondWithError('Something went wrong.', $th->getMessage());
        }
    }

    public function notifications()
    {
        try {
            $driver = auth('driver')->user();
            $schedules_notifications = Schedule::where('d_id', $driver->id)
            ->where('status', Schedule::STATUS_PUBLISHED)
                ->where('date', now()->format('Y-m-d'))
                ->whereBetween('time', [
                    now()->format('H:i:s'),
                    now()->addMinutes(15)->format('H:i:s')
                ])
                ->get();
            return $this->respondWithSuccess($schedules_notifications, 'Notifications retrieved successfully.', 'DRIVER_NOTIFICATIONS_RETRIEVED');
        } catch (\Throwable $th) {
            return $this->respondWithError('Something went wrong.', $th->getMessage());
        }
    }
}
