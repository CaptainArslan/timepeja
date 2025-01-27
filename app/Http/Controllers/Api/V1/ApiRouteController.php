<?php

namespace App\Http\Controllers\Api\V1;

use PDF;
use App\Models\Route;
use Illuminate\Http\Request;
use App\Models\Pdf as ModelsPdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiRouteController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $routes = Route::with('organization')
            ->ByOrganization($manager->organization_id)
            ->search($request->search)
            ->latest()
            ->paginate(getPaginated($request->limit));

        return $this->respondWithSuccess($routes, 'Organization Routes', 'ORGANIZATION_ROUTES');
    }

    public function store(Request $request): jsonResponse
    {
        $manager = auth('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $routeExists = Route::where('organization_id', $manager->organization_id)
            ->where('number', $request->number)
            ->exists();

        if ($routeExists) {
            return $this->respondWithError('Route Number already exists');
        }

        try {
            
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage(), 'API_ERROR');
        }

        return $this->respondWithError('Error Occured while route creation.');
    }

    public function show($id): jsonResponse
    {
        $manager  = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $route = Route::with('organization')->withTrashed()->findOrFail($id);

        if (!$route) {
            return $this->respondWithError('Route not found');
        }

        if ($route->organization_id != $manager->organization_id) {
            return $this->respondWithError('Route not belongs to your organization');
        }

        return $this->respondWithSuccess($route, 'Get Route', 'API_GET_ROUTE');
    }

    public function update(Request $request, $id)
    {
        try {
            $route = Route::findOrFail($id);

            $validator = Validator::make(
                $request->all(),
                [
                    'number' => ['required', 'string',],
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
                return $this->respondWithError(implode(",", $validator->errors()->all()));
            }

            try {
                DB::beginTransaction();

                $routes = [];
                $data = Route::where('number', $route->number)
                    ->where('o_id', $route->o_id)
                    ->where('status', Route::STATUS_ACTIVE)
                    ->get();

                if (!$data) {
                    return $this->respondWithError('Route not found');
                }

                $route1 = $data[0];
                $route1->number = $request->number;
                $route1->from = $request->from;
                $route1->from_longitude = $request->from_longitude;
                $route1->from_latitude = $request->from_latitude;
                $route1->to = $request->to;
                $route1->to_longitude = $request->to_longitude;
                $route1->to_latitude = $request->to_latitude;
                $route1->name =  $request->number . ' - ' . $request->to . ' To ' . $request->from;
                $route1->way_points = $request->way_points ? json_encode($request->way_points) : null;

                $route1Save = $route1->save();
                if (!$route1Save) {
                    return $this->respondWithError('Error occurred while updating route 1.');
                }
                $routes[] = $route1;

                $route2 = $data[1];
                $route2->number = $request->number;
                $route2->to = $request->from;
                $route2->to_longitude = $request->from_longitude;
                $route2->to_latitude = $request->from_latitude;
                $route2->from = $request->to;
                $route2->from_longitude = $request->to_longitude;
                $route2->from_latitude = $request->to_latitude;
                $route2->name = $request->number . ' - ' . $request->from . ' To ' . $request->to;
                $route2->way_points = $request->way_points ? json_encode($request->way_points) : null;
                $route2Save =  $route2->save();
                if (!$route2Save) {
                    return $this->respondWithError('Error occurred while updating route 2.');
                }
                $routes[] = $route2;
                if ($route1Save && $route2Save) {
                    DB::commit();
                    return $this->respondWithSuccess($routes, 'Route updated successfully', 'API_ROUTE_UPDATED');
                } else {
                    DB::rollBack();
                    return $this->respondWithError('Error occurred while updating routes.');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Route id not found');
            // throw new NotFoundHttpException('Route id not found');
        }
    }

    public function destroy($id): jsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required',
            'numeric',
            'exists:routes,id'
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
            return $this->respondWithDelete('Route deleted successfully', 'API_ROUTE_DELETED');
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Route id not found');
            // throw new NotFoundHttpException('Driver id not found');
        }
    }

    public function getRoute(): jsonResponse
    {
        try {
            $manager = auth('manager')->user();
            $routes = Route::where('o_id', $manager->o_id)
                ->where('status', Route::STATUS_ACTIVE)
                ->get();
            return $this->respondWithSuccess($routes, 'Organization Routes', 'ORGANIZATION_ROUTES');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while fetching organization driver');
            throw $th;
        }
    }

    public function createPdf(Request $request)
    {
        try {
            $manager = auth('manager')->user();
            $routes = Route::where('o_id', $manager->o_id)
                ->with('organization:id,name,branch_name,branch_code,email,phone,address,code')
                ->get();
            $data = [
                'routes' => $routes->toArray(),
                'request' => $request->all()
            ];
            $pdf = PDF::loadview('pdf.route', $data);
            $pdf->setPaper('A4', 'landscape');

            $filename = date('Ymd_His') . '_Driver_Report.pdf'; // Generate a unique filename
            $filePath = public_path('uploads/pdf/' . $filename); // Get the full file path

            $pdf->save($filePath); // Save the PDF to the specified folder

            $pdfModel = new ModelsPdf();
            $pdfModel->url = asset('/uploads/pdf/' . $filename);

            if ($pdfModel->save()) {
                return $this->respondWithSuccess($pdfModel, 'Pdf Created Successfully', 'LOG_REPORT_PDF_CREATED_SUCCESSFULLY');
            } else {
                // Delete the saved PDF file if model saving failed
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                return $this->respondWithError('Error occurred while creating the PDF. Failed to save the model.');
            }
        } catch (\Throwable $th) {
            // Delete the saved PDF file if an exception occurred
            // if (file_exists($filePath)) {
            //     unlink($filePath);
            // }
            return $this->respondWithError('Error occurred while creating the PDF: ' . $th->getMessage());
        }
    }
}
