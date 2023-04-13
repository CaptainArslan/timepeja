<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\Route;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ScheduleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $manager = auth('manager')->user();
            $schedule = Schedule::where('o_id', $manager->o_id)
                ->with('organizations:id,name')
                ->with('routes:id,name,number,from,to')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->get();
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
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'o_id' => ['required',  'numeric', 'exists:organizations,id'],
            'route_id' => ['required', 'numeric', 'exists:routes,id'],
            'v_id' => ['required', 'numeric', 'exists:vehicles,id'],
            'd_id' => ['required', 'numeric', 'exists:drivers,id'],
            'date' => ['required', 'date'],
            'time' => ['required'],
        ], [
            // 'o_id.required' => 'Oragnization is required',
            // 'o_id.numeric' => 'Organization id in numeric required',
            // 'o_id.exists' => 'Invalid organization id',

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
        ]);

        if ($validator->fails()) {
            return $this->respondWithError("Please fill the form correctly");
            // return $this->respondWithSuccess($validator->errors()->all(), 'message', 'Validation Error');
        }

        $manager = auth('manager')->user();
        $data = [
            'o_id'         => $manager->o_id,
            'u_id'         => $manager->id,
            'route_id'     => $request->route_id,
            'v_id'         => $request->v_id,
            'd_id'         => $request->d_id,
            'date'         => $request->date,
            'time'         => $request->time,
            'status'       => Schedule::STATUS_DRAFT,
        ];

        $save = Schedule::create($data);
        if (!$save) {
            return $this->respondWithError('Error Occured while creating schedule');
        }
        $data = Schedule::where('id', $save->id)
            ->with('organizations:id,name')
            ->with('routes:id,name,number,from,to')
            ->with('vehicles:id,number')
            ->with('drivers:id,name')
            ->where('status', Schedule::STATUS_DRAFT)
            ->get();
        return $this->respondWithSuccess($data, 'Schedule Creadted Successfully', 'SCHEDULE_CREATED');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'o_id' => ['required', 'numeric', 'exists:organizations,id'],
            'route_id' => ['required', 'numeric', 'exists:routes,id'],
            'v_id' => ['required', 'numeric', 'exists:vehicles,id'],
            'd_id' => ['required', 'numeric', 'exists:drivers,id'],
            'date' => ['required', 'date'],
            'time' => ['required'],
            'id' => ['numeric', 'exists:schedules,id'],
        ], [
            'o_id.required' => 'Oragnization is required',
            'o_id.numeric' => 'Organization id in numeric required',
            'o_id.exists' => 'Invalid organization id',

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
        ]);

        if ($validator->fails()) {
            return $this->respondWithError('Please fill the form correctly');
            // return $this->respondWithSuccess($validator->errors()->all(), 'message', 'VALIDATION_ERROR');
        }

        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->o_id = $request->input('o_id');
            $schedule->route_id = $request->input('route_id');
            $schedule->v_id = $request->input('v_id');
            $schedule->d_id = $request->input('d_id');
            $schedule->date = $request->input('date');
            $schedule->time = $request->input('time');

            $schedule->save();

            return $this->respondWithSuccess($schedule, 'Schedule updated successfully', 'SCHEDULE_UPDATED');
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Invalid Schedule id');
            // throw new NotFoundHttpException('Schedule id not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $o_id
     * @return void
     */
    public function getOrganizationData()
    {
        $manager = auth('manager')->user();
        try {
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

            $schedule = Schedule::with('organizations:id,name')
                ->with('routes:id,name,number,from,to')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->where('o_id', $manager->o_id)
                ->whereDate('date', date('Y-m-d'))
                ->where('status', Schedule::STATUS_PUBLISHED)
                ->select('id', 'o_id', 'route_id', 'v_id', 'd_id', 'date', 'time', 'status', 'created_at')
                ->get();

            $published = $schedule->where('status', Schedule::STATUS_PUBLISHED);
            $created = $schedule->where('status', Schedule::STATUS_DRAFT);
            $data['routes'] = $routes;
            $data['vehicles'] = $vehicles;
            $data['drivers'] = $drivers;
            $data['published_schedule'] = $published;
            $data['created_schedule'] = $created;
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('User not found');
        }
        if (!$manager->o_id) {
            return $this->respondWithError('Organization id is required');
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
    public function publish(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => ['required'],
            'ids.*' => ['integer', 'exists:schedules,id'],
        ], [
            'ids.required' => 'IDs are required',
            'ids.*.integer' => 'ID must be an integer',
            'ids.*.exists' => 'Invalid ID provided',
        ]);

        if ($validator->fails()) {
            // $errors = $validator->errors()->all();
            // return $this->respondWithError(implode(", ", $errors));
            return $this->respondWithError('Invalid schedule id');
        }

        $ids = (array) $request->input('ids');

        try {
            $updatedIds = [];
            $error = false;

            DB::transaction(function () use ($ids, &$updatedIds, &$error) {
                foreach ($ids as $id) {
                    $schedule = Schedule::findOrFail($id);
                    $schedule->status = Schedule::STATUS_PUBLISHED;
                    if (!$schedule->save()) {
                        $error = true;
                        break;
                    }
                    $updatedIds[] = $id;
                }
                if ($error) {
                    throw new Exception("Failed to update one or more Schedules");
                }
            });

            return $this->respondWithSuccess($updatedIds, 'Schedules published successfully', 'PUBLISH_SCHEDULE');
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
    public function draft(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => ['required'],
            'ids.*' => ['integer', 'exists:schedules,id'],
        ], [
            'ids.required' => 'IDs are required',
            'ids.*.integer' => 'ID must be an integer',
            'ids.*.exists' => 'Invalid ID provided',
        ]);

        if ($validator->fails()) {
            // $errors = $validator->errors()->all();
            return $this->respondWithError('Invalid schedule id');
        }

        $ids = (array) $request->input('ids');

        try {
            $updatedIds = [];
            $error = false;

            DB::transaction(function () use ($ids, &$updatedIds, &$error) {
                foreach ($ids as $id) {
                    $schedule = Schedule::findOrFail($id);
                    $schedule->status = Schedule::STATUS_DRAFT;
                    if (!$schedule->save()) {
                        $error = true;
                        break;
                    }
                    $updatedIds[] = $id;
                }
                if ($error) {
                    throw new Exception("Failed to update one or more Schedules");
                }
            });

            return $this->respondWithSuccess($updatedIds, 'Schedules draft successfully', 'DRAFT_SCHEDULE');
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function getPublishedScheduleByDate($date)
    {
        $validator = Validator::make(['date' => $date], [
            'date' => ['required', 'date']
        ], [
            'id.required' => 'Date is required',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError(implode(',', $validator->errors()->all()));
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
                ->get();
            return $this->respondWithSuccess($schedules, 'Schedule by date', 'SCHEDULE_BY_DATE');
        } catch (\Throwable $th) {
            return $this->respondWithError('An error occurred while fetching schedules for this date.' . $th->getMessage());
        }
    }

    public function getCreatedScheduleByDate($date)
    {
        $validator = Validator::make(['date' => $date], [
            'date' => ['required', 'date']
        ], [
            'id.required' => 'Date is required',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError(implode(',', $validator->errors()->all()));
        }

        try {
            $manager = auth('manager')->user();
            $schedules = Schedule::with('organizations:id,name')
                ->with('routes:id,name,number,from,to')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->where('o_id', $manager->o_id)
                ->whereDate('date', $date)
                ->where('status', Schedule::STATUS_DRAFT)
                ->get();
            return $this->respondWithSuccess($schedules, 'Schedule by date', 'SCHEDULE_BY_DATE');
        } catch (\Throwable $th) {
            return $this->respondWithError('An error occurred while fetching schedules for this date.');
        }
    }
}
