<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\Driver;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\FcmNotificationEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Manager\Schedule\UpdateScheduleRequest;
use App\Http\Requests\Manager\Schedule\PublishScheduleRequest;

class ApiScheduleController extends BaseController
{
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

    public function getPublishedScheduleByDate($date): JsonResponse
    {
        $validator = Validator::make(['date' => $date], [
            'date' => ['required', 'date']
        ], [
            'date.required' => 'Date is required',
            'date.date' => 'Invalid date format'
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
                'organization',
                'route',
                'vehicle',
                'driver'
            ])
            ->where('date', $date)
            ->where('status', Schedule::STATUS_PUBLISHED)
            ->get();

        return $this->respondWithSuccess($schedules, 'Schedule by date', 'PUBLISHED_SCHEDULE_BY_DATE');
    }

    public function getCreatedScheduleByDate($date): JsonResponse
    {
        $validator = Validator::make(['date' => $date], [
            'date' => ['required', 'date']
        ], [
            'date.required' => 'Date is required',
            'date.date' => 'Invalid date format'
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
                'organization',
                'route',
                'vehicle',
                'driver'
            ])
            ->where('date', $date)
            ->where('status', Schedule::STATUS_DRAFT)
            ->get();
        return $this->respondWithSuccess($schedules, 'Schedule by date', 'CREATED_SCHEDULE_BY_DATE');
    }

    public function replicate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'schedule_ids' => ['required', 'array', 'exists:schedules,id'],
            'date' => ['required', 'date'],
        ], [
            'schedule_ids.required' => 'Schedules ids required',
            'schedule_ids.array' => 'Schedules ids must be an array',

            'date.required' => 'Date is required',
            'date.date' => 'Must have to be in date',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError(implode(',', $validator->errors()->all()));
        }


        $manager = auth('manager')->user();
        $schedules = Schedule::whereIn('id', $request->schedule_ids)->get();
        $date = $request->date;
        try {
            DB::transaction(function () use ($schedules, $manager, $date) {
                foreach ($schedules as $schedule) {
                    $this->replicateSingleSchedule($schedule, $manager, $date);
                }
            });
            return $this->respondWithSuccess([], 'Successfully replicated', 'REPLICATED_SUCCESSFULLY');
        } catch (\Exception $e) {
            return $this->respondWithError('Error Occrued while replicating scehdule');
        }
    }

    private function replicateSingleSchedule($schedule, $user, $date)
    {
        $newSchedule = Schedule::create([
            'u_id' => $user->id,
            'date' => $date,
            'o_id' => $schedule->o_id,
            'v_id' => $schedule->v_id,
            'd_id' => $schedule->d_id,
            'time' => $schedule->time,
            'route_id' => $schedule->route_id,
            'status' => Schedule::STATUS_DRAFT
        ]);

        if (!$newSchedule) {
            throw new \Exception('Error occurred while replicating schedule.');
        }
    }

    public function activeVehicle(Request $request)
    {
        try {
            $manager = auth('manager')->user();

            if (!$manager) {
                return $this->respondWithError('Manager not found');
            }

            $date = $request->date ?? date('Y-m-d');

            $schedule = Schedule::where('o_id', $manager->o_id)
                ->when($request->string, function ($query) use ($request) {
                    $query->whereHas('vehicles', function ($query) use ($request) {
                        $query->where('number', 'like', '%' . $request->string . '%');
                    });
                })
                ->with('vehicles:id,number')
                ->where('status', Schedule::STATUS_PUBLISHED)
                ->where('date', $date)
                ->where('trip_status', Schedule::TRIP_STATUS_INPROGRESS)
                ->select('id', 'v_id')
                ->get();
            return $this->respondWithSuccess($schedule, 'Oganization active schedule', 'ORGANIZATION_ACTIVE_SCHEDULE');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while fetching organization schedule');
        }
    }
}
