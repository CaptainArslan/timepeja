<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiRouteController extends BaseController
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
            // DRIVER_LIMIT_PER_PAGE
            $routes = Route::where('o_id', $manager->o_id)
                ->where('status', Route::STATUS_ACTIVE)
                ->paginate(Route::ROUTE_LIMIT_PER_PAGE);
            if ($routes->isEmpty()) {
                return $this->respondWithError('No data found');
            }
            return $this->respondWithSuccess($routes, 'Organization Routes', 'ORGANIZATION_ROUTES');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while fetching organization driver');
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): jsonResponse
    {
        return $this->respondWithError('Method not allowed');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): jsonResponse
    {
        $validator = Validator::make($request->all(), [
            'number' => ['required', 'int', 'unique:routes,number'],
            'name' => ['nullable', 'string'],
            'from' => ['required', 'string'],
            'from_longitude' => ['nullable', 'string'],
            'from_latitude' => ['nullable', 'string'],
            'to' => ['required', 'string'],
            'to_longitude' => ['nullable', 'string'],
            'to_latitude' => ['nullable', 'string'],
        ], [
            'number.required' => 'Route number is required',
            'number.unique' => 'Route number already exists',
            'from.required' => 'Route from location is required',
            'to.required' => 'Route to location is required',
            'from_longitude.required' => 'Route from longitude is required',
            'from_latitude.required' => 'Route from latitude is required',
            'to_longitude.required' => 'Route to longitude is required',
            'to_latitude.required' => 'Route to latitude is required',
            'number.int' => 'Route number must be an integer',
            'from.string' => 'Route from location must be a string',
            'to.string' => 'Route to location must be a string',
            'from_longitude.string' => 'Route from longitude must be a string',
            'from_latitude.string' => 'Route from latitude must be a string',
            'to_longitude.string' => 'Route to longitude must be a string',
            'to_latitude.string' => 'Route to latitude must be a string',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        $manager = auth('manager')->user();
        try {
            DB::beginTransaction();

            $routes = [];

            // 1st route
            $route1 = new Route();
            $route1->u_id = $manager->id;
            $route1->o_id = $manager->o_id;
            $route1->number = $request->input('number');

            $route1->to = $request->input('to');
            $route1->to_longitude = $request->input('to_longitude');
            $route1->to_latitude = $request->input('to_latitude');

            $route1->from = $request->input('from');
            $route1->from_longitude = $request->input('from_longitude');
            $route1->from_latitude = $request->input('from_latitude');

            $route1->name = $request->input('number') . ' - ' . $request->input('from') . ' To ' . $request->input('to');
            if (!$route1->save()) {
                throw new \Exception('Error occurred while creating 1st route.');
            }
            $routes[] = $route1;

            // 2nd route
            $route2 = new Route();
            $route2->u_id = $manager->id;
            $route2->o_id = $manager->o_id;
            $route2->number = $request->input('number');

            $route2->to = $request->input('from');
            $route2->to_longitude = $request->input('from_longitude');
            $route2->to_latitude = $request->input('from_latitude');

            $route2->from = $request->input('to');
            $route2->from_longitude = $request->input('to_longitude');
            $route2->from_latitude = $request->input('to_latitude');

            $route2->name = $request->input('number') . ' - ' . $request->input('to') . ' To ' . $request->input('from');
            if (!$route2->save()) {
                throw new \Exception('Error occurred while creating 2nd route.');
            }
            $routes[] = $route2;

            DB::commit();

            return $this->respondWithSuccess($routes, 'Routes created successfully', 'API_ROUTE_CREATED');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->respondWithError($e->getMessage(), 'API_ERROR');
        }

        return $this->respondWithError('Error Occured while route creation.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): jsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric', 'exists:routes,id']
        ], [
            'id.required' => 'Route id is required',
            'id.exists' => 'Route id not found'
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }
        try {
            // $manager = auth('manager')->user();
            $route = Route::findOrFail($id)
                // ->where('o_id', $manager->o_id)
                ->where('status', Route::STATUS_ACTIVE)
                ->firstOrFail();

            return $this->respondWithSuccess($route, 'Get Route', 'API_GET_ROUTE');
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Route id not found');
            // throw new NotFoundHttpException('Route id not found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): jsonResponse
    {
        return $this->respondWithError('Method not allowed', 'API_METHOD_NOT_ALLOWED');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): jsonResponse
    {
        try {
            $route = Route::findOrFail($id);
            $validator = Validator::make(
                $request->all(),
                [
                    'number' => ['required', 'string', 'unique:routes,number,' . $id],
                    'name' => ['nullable', 'string'],
                    'from' => ['required', 'string'],
                    'from_longitude' => ['nullable', 'string'],
                    'from_latitude' => ['nullable', 'string'],
                    'to' => ['required', 'string'],
                    'to_longitude' => ['nullable', 'string'],
                    'to_latitude' => ['nullable', 'string'],
                ],
                [
                    'number.required' => 'Route number is required',
                    'number.unique' => 'Route number already exists',
                    'from.required' => 'Route from location is required',
                    'to.required' => 'Route to location is required',
                    'from_longitude.required' => 'Route from longitude is required',
                    'from_latitude.required' => 'Route from latitude is required',
                    'to_longitude.required' => 'Route to longitude is required',
                    'to_latitude.required' => 'Route to latitude is required',
                    'name.required' => 'Route name is required',
                    'name.string' => 'Route name must be a string',
                    'from.string' => 'Route from location must be a string',
                    'from_longitude.string' => 'Route from longitude must be a string',
                    'from_latitude.string' => 'Route from latitude must be a string',
                    'to.string' => 'Route to location must be a string',
                    'to_longitude.string' => 'Route to longitude must be a string',
                    'to_latitude.string' => 'Route to latitude must be a string',
                ]
            );

            if ($validator->fails()) {
                return $this->respondWithError($validator->errors()->first());
            }


            $route->number = $request->input('number');
            $route->from = $request->input('from');
            $route->from_longitude = $request->input('from_longitude');
            $route->from_latitude = $request->input('from_latitude');
            $route->to = $request->input('to');
            $route->to_longitude = $request->input('to_longitude');
            $route->to_latitude = $request->input('to_latitude');
            $route->name =  $request->input('number') . ' - ' . $request->input('to') . ' To ' . $request->input('from');
            if (!$route->save()) {
                throw new \Exception('Error occurred while updating route.');
            }

            return $this->respondWithSuccess($route, 'Route updated successfully', 'API_ROUTE_UPDATED');
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Route id not found');
            // throw new NotFoundHttpException('Route id not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): jsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required', 'numeric', 'exists:routes,id'
        ], [
            'id.required' => 'Driver id is required',
            'id.exists' => 'Driver id not found'
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $route = Route::findOrFail($id);
            $route->delete();
            return $this->respondWithSuccess($route, 'Route deleted successfully', 'API_ROUTE_DELETED');
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Route id not found');
            // throw new NotFoundHttpException('Driver id not found');
        }
    }

    /**
     * Search routes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(): jsonResponse
    {
        try {
            $string = request()->input('string');
            $manager = auth('manager')->user();
            $routes = Route::where('name', 'LIKE', '%' . $string . '%')
                ->orWhere('number', 'LIKE', '%' . $string . '%')
                ->orWhere('from', 'LIKE', '%' . $string . '%')
                ->orWhere('to', 'LIKE', '%' . $string . '%')
                // ->orWhere('from_longitude', 'LIKE', '%' . $string . '%')
                // ->orWhere('from_latitude', 'LIKE', '%' . $string . '%')
                // ->orWhere('to_longitude', 'LIKE', '%' . $string . '%')
                // ->orWhere('to_latitude', 'LIKE', '%' . $string . '%')
                ->where('o_id', $manager->o_id)
                ->select('id', 'name')
                ->get();
            // ->paginate(Route::ROUTE_LIMIT_PER_PAGE);
            if ($routes->isEmpty()) {
                return $this->respondWithError('No Vehicle found');
            }
            return $this->respondWithSuccess($routes, 'Routes retrieved successfully', 'API_ROUTE_SEARCH_RESULT');
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('No routes found');
        }
    }
}
