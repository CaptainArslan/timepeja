<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\Route;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiScheduleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        try {
            $manager = auth('manager')->user();
            $schedule = Schedule::where('o_id', $manager->o_id)
                ->with('organizations:id,name')
                ->with('routes:id,name,number,from,to')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                // ->select('id', 'o_id', 'route_id', 'v_id', 'd_id', 'date', 'time', 'status', 'is_delay', 'trip_status', 'created_at', 'updated_at', 'delayed_reason')
                ->get();
            // if ($schedule->isEmpty()) {
            //     return $this->respondWithError('No data found');
            // }
            return $this->respondWithSuccess($schedule, 'Oganization All Schedule', 'ORGANIZATION_SCHEDULE');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while fetching organization schedule');
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): JsonResponse
    {
        return $this->respondWithError($request->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'route_id' => ['required', 'numeric', 'exists:routes,id'],
            'v_id' => ['required', 'numeric', 'exists:vehicles,id'],
            'd_id' => ['required', 'numeric', 'exists:drivers,id'],
            'date' => ['required', 'date'],
            'time' => ['required', 'after_or_equal:' . Carbon::now()->toDateString()],
        ], [
            'route_id.required' => 'Route is required',
            'route_id.numeric' => 'Route id in numeric required',
            'route_id.exists' => 'Invalid route id',

            'v_id.required' => 'Vehicle is required',
            'v_id.numeric' => 'Vehicle id in numeric required',
            'v_id.exists' => 'Invalid vehicle id',

            'd_id.required' => 'Driver is required',
            'd_id.numeric' => 'Driver id in numeric required',
            'd_id.exists' => 'Invalid driver id',

            'date.required' => 'Date is required',

            'time.required' => 'Time is required',
            'time.after_or_equal' => 'Time must be greater than or equal to current time',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
            // return $this->respondWithSuccess($validator->errors()->all(), 'message', 'Validation Error');
        }

        $manager = auth('manager')->user();

        $schedule = new Schedule();
        $schedule->o_id = $manager->o_id;
        $schedule->u_id = $manager->id;
        $schedule->route_id = intval($request->input('route_id'));
        $schedule->v_id = intval($request->input('v_id'));
        $schedule->d_id = intval($request->input('d_id'));
        $schedule->date = $request->input('date');
        $schedule->time = $request->input('time');
        $schedule->status = Schedule::STATUS_DRAFT;
        $save = $schedule->save();
        if (!$save) {
            return $this->respondWithError('Error Occured while creating schedule');
        }
        $data = $schedule->load([
            'organizations:id,name',
            'routes:id,name,number,from,to',
            'vehicles:id,number',
            'drivers:id,name'
        ]);
        return $this->respondWithSuccess($data, 'Schedule Creadted Successfully', 'SCHEDULE_CREATED');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['exists:schedules,id', 'required']
        ], [
            'id.exists' => 'Invalid schedule id',
            'id.required' => 'Schedule id is required'
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $schedule = Schedule::with('organizations:id,name')
                ->with('routes:id,name,number,from,to')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->where('status', Schedule::STATUS_DRAFT)
                ->findOrFail($id);

            return $this->respondWithSuccess($schedule, 'Get schedule', 'API_GET_SCHEDULE');
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Schedule id not found');
            throw new NotFoundHttpException('Schedule id not found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): JsonResponse
    {
        return $this->respondWithError($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            // 'id' => ['numeric', 'exists:schedules,id'],
            'route_id' => ['required', 'numeric', 'exists:routes,id'],
            'v_id' => ['required', 'numeric', 'exists:vehicles,id'],
            'd_id' => ['required', 'numeric', 'exists:drivers,id'],
            'date' => ['required', 'date'],
            'time' => ['required'],
        ], [
            'route_id.required' => 'Route is required',
            'route_id.numeric' => 'Route id in numeric required',
            'route_id.exists' => 'Invalid route id',

            'v_id.required' => 'Vehicle is required',
            'v_id.numeric' => 'Vehicle id in numeric required',
            'v_id.exists' => 'Invalid vehicle id',

            'd_id.required' => 'Driver is required',
            'd_id.numeric' => 'Driver id in numeric required',
            'd_id.exists' => 'Invalid driver id',

            'date.required' => 'Date is required',
            'date.date' => 'Date is invalid',

            'time.required' => 'Time is required',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            // $schedule->o_id = $request->input('o_id');
            $schedule = Schedule::findOrFail($id);
            $schedule->route_id = intval($request->input('route_id'));
            $schedule->v_id = intval($request->input('v_id'));
            $schedule->d_id = intval($request->input('d_id'));
            $schedule->date = $request->input('date');
            $schedule->time = $request->input('time');
            $schedule->save();

            $data = $schedule->load([
                'organizations:id,name',
                'routes:id,name,number,from,to',
                'vehicles:id,number',
                'drivers:id,name'
            ]);

            return $this->respondWithSuccess($data, 'Schedule updated successfully', 'SCHEDULE_UPDATED');
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('Schedule id not found');
            return $this->respondWithError('Invalid Schedule id');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['exists:schedules,id', 'required']
        ], [
            'id.exists' => 'Invalid schedule id',
            'id.required' => 'Schedule id is required'
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->delete();
            return $this->respondWithSuccess($schedule->id, 'Schedule deleted successfully', 'API_SCHEDULE_DELETED');
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Invalid Schedule id');
            throw new NotFoundHttpException('Schedule id not found');
        }
    }

    /**
     * Get organization data
     *
     * @return JsonResponse
     */
    public function getOrganizationData(): JsonResponse
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
                ->select('id', 'number as  name')
                ->get();

            $drivers = Driver::where('o_id', $manager->o_id)
                ->where('status', Driver::STATUS_ACTIVE)
                ->select('id', 'name')
                ->get();

            $schedules = Schedule::with('organizations:id,name')
                ->with('routes:id,name,number,from,to')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->where('o_id', $manager->o_id)
                ->whereDate('date', date('Y-m-d'))
                ->select('id', 'o_id', 'route_id', 'v_id', 'd_id', 'date', 'time', 'status', 'created_at')
                ->get();
            // Initialize two empty arrays
            $publishedSchedules = [];
            $draftSchedules = [];

            // Loop through the schedules
            // Loop through each schedule and add it to the appropriate array based on its status
            foreach ($schedules as $schedule) {
                if ($schedule->status == Schedule::STATUS_PUBLISHED) {
                    $publishedSchedules[] = $schedule;
                } else {
                    $draftSchedules[] = $schedule;
                }
            }

            $data = [
                'routes' => $routes,
                'vehicles' => $vehicles,
                'drivers' => $drivers,
                'published_schedule' => $publishedSchedules,
                'created_schedule' => $draftSchedules
            ];

            // store data in cache
            Cache::put('ORGANIZATION_ROUTE_VEHICLE_DRIVER_SCHEDULE_DATA_' . $manager->o_id, $data, 60 * 60 * 24);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('Data not found ' . $e->getMessage());
        }

        return $this->respondWithSuccess(
            $data,
            'Organization route, vehicle, driver data, published and created schedule',
            'ORGANIZATION_ROUTE_VEHICLE_DRIVER_DATA_SCHEDULE'
        );
    }

    /**
     * Publish schedule
     *
     * @param Request $request
     * @return void
     */
    public function publish(Request $request): JsonResponse
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
            // $errors = $validator->errors()->all();
            // return $this->respondWithError(implode(", ", $errors));
            return $this->respondWithError($validator->errors()->first());
            // return $this->respondWithError('Invalid schedule id');
        }

        $ScheduleIds = (array) $request->input('Schedule_ids');

        try {
            $updatedScheduleIds = [];
            $error = false;

            DB::transaction(function () use ($ScheduleIds, &$updatedScheduleIds, &$error) {
                foreach ($ScheduleIds as $id) {
                    $schedule = Schedule::findOrFail($id);
                    $schedule->status = Schedule::STATUS_PUBLISHED;
                    if (!$schedule->save()) {
                        $error = true;
                        break;
                    }
                    $updatedScheduleIds[] = $id;
                }
                if ($error) {
                    throw new Exception("Failed to update one or more Schedules");
                }
            });

            return $this->respondWithSuccess($updatedScheduleIds, 'Schedules published successfully', 'PUBLISH_SCHEDULE');
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Make schedule draft
     *
     * @param Request $request
     * @return void
     */
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
            // $errors = $validator->errors()->all();
            // return $this->respondWithError(implode(", ", $errors));
            return $this->respondWithError($validator->errors()->first());
            // return $this->respondWithError('Invalid schedule id');
        }

        $ScheduleIds = (array) $request->input('Schedule_ids');

        try {
            $updatedScheduleIds = [];
            $error = false;

            DB::transaction(function () use ($ScheduleIds, &$updatedScheduleIds, &$error) {
                foreach ($ScheduleIds as $id) {
                    $schedule = Schedule::findOrFail($id);
                    $schedule->status = Schedule::STATUS_DRAFT;
                    if (!$schedule->save()) {
                        $error = true;
                        break;
                    }
                    $updatedScheduleIds[] = $id;
                }
                if ($error) {
                    throw new Exception("Failed to update one or more Schedules");
                }
            });

            return $this->respondWithSuccess($updatedScheduleIds, 'Schedules draft successfully', 'DRAFT_SCHEDULE');
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * published publish schedule by date
     *
     * @param [type] $date
     * @return JsonResponse
     */
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

        try {
            $manager = auth('manager')->user();
            $schedules = Schedule::with('organizations:id,name')
                ->with('routes:id,name,number,from,to')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->where('o_id', $manager->o_id)
                ->whereDate('date', $date)
                ->where('status', Schedule::STATUS_PUBLISHED)
                ->select('id', 'o_id', 'route_id', 'v_id', 'd_id', 'date', 'time', 'status')
                ->get();

            Cache::put('PUBLISHED_SCHEDULE_' . $manager->o_id, $schedules, now()->addDay(1));

            return $this->respondWithSuccess($schedules, 'Schedule by date', 'SCHEDULE_BY_DATE');
        } catch (\Throwable $th) {
            return $this->respondWithError('An error occurred while fetching schedules for this date.' . $th->getMessage());
        }
    }

    /**
     * get create schedule by date
     *
     * @param [type] $date
     * @return JsonResponse
     */
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

        try {
            $manager = auth('manager')->user();
            // return $manager;
            $schedules = Schedule::with('organizations:id,name')
                ->with('routes:id,name,number,from,to')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->where('o_id', $manager->o_id)
                ->whereDate('date', $date)
                ->where('status', Schedule::STATUS_DRAFT)
                ->select('id', 'o_id', 'route_id', 'v_id', 'd_id', 'date', 'time', 'status')
                ->get();

            // store in cache
            Cache::put('CREATED_SCHEDULE_' . $manager->o_id, $schedules, now()->addDay(1));

            return $this->respondWithSuccess($schedules, 'Schedule by date', 'SCHEDULE_BY_DATE');
        } catch (\Throwable $th) {
            return $this->respondWithError('An error occurred while fetching schedules for this date.' . $th->getMessage());
        }
    }
}
