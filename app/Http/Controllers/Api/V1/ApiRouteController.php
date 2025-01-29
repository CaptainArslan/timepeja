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
use App\Http\Requests\Manager\Route\CreateRouteRequest;
use App\Http\Requests\Manager\Route\UpdateRouteRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApiRouteController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $routes = Route::ByOrganization($manager->organization_id)
            ->search($request->search)
            ->latest()
            ->paginate(getPaginated($request->limit));

        return $this->respondWithSuccess($routes, 'Organization Routes', 'ORGANIZATION_ROUTES');
    }

    public function store(CreateRouteRequest $request): jsonResponse
    {
        $manager = auth('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $routeExists = Route::byOrganization($manager->organization_id)
            ->where('number', $request->number)
            ->exists();

        if ($routeExists) {
            return $this->respondWithError('Route Number already exists');
        }

        try {
            $createdRoutes = DB::transaction(function () use ($manager, $request) {
                $routes = [];
                $routes  = Route::create([
                    'organization_id' => $manager->organization_id,
                    'name' =>  $request->number . ' - ' . $request->from['city'] . ' To ' . $request->to['city'],
                    'number' => $request->number,
                    'from' => $request->from,
                    'to' => $request->to,
                    'status' => Route::STATUS_ACTIVE,
                    'way_points' => $request->way_points,
                ]);

                $routes = Route::create([
                    'organization_id' => $manager->organization_id,
                    'name' =>  $request->number . ' - ' . $request->to['city'] . ' To ' . $request->from['city'],
                    'number' => $request->number,
                    'from' => $request->from,
                    'to' => $request->to,
                    'status' => Route::STATUS_ACTIVE,
                    'way_points' => $request->way_points,
                ]);
                return $routes;
            });

            return $this->respondWithSuccess($createdRoutes, 'Route created successfully', 'API_ROUTE_CREATED');
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage(), 'API_ERROR');
        }
    }

    public function show($id): jsonResponse
    {
        $manager  = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $route = Route::withTrashed()->findOrFail($id);

        if (!$route) {
            return $this->respondWithError('Route not found');
        }

        if ($route->organization_id != $manager->organization_id) {
            return $this->respondWithError('Route not belongs to your organization');
        }

        return $this->respondWithSuccess($route, 'Get Route', 'API_GET_ROUTE');
    }

    public function update(UpdateRouteRequest $request, $id)
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $route = Route::withTrashed()->findOrFail($id);

        if (!$route) {
            return $this->respondWithError('Route not found');
        }

        try {
            DB::beginTransaction();

            $routes = Route::where('number', $route->number)
                ->ByOrganization($manager->organization_id)
                ->where('status', Route::STATUS_ACTIVE)
                ->get()->toArray();

            if (!$routes) {
                return $this->respondWithError('Route not found');
            }

            $updatedRoutes = DB::transaction(function () use ($routes, $manager, $request) {
                $route1 = $routes[0];
                $route2 = $routes[1];

                $route1->update([
                    'organization_id' => $manager->organization_id,
                    'name' =>  $request->number . ' - ' . $request->from['city'] . ' To ' . $request->to['city'],
                    'number' => $request->number,
                    'from' => $request->from,
                    'to' => $request->to,
                    'status' => Route::STATUS_ACTIVE,
                    'way_points' => $request->way_points,
                ]);

                $route2->update([
                    'organization_id' => $manager->organization_id,
                    'name' =>  $request->number . ' - ' . $request->to['city'] . ' To ' . $request->from['city'],
                    'number' => $request->number,
                    'from' => $request->from,
                    'to' => $request->to,
                    'status' => Route::STATUS_ACTIVE,
                    'way_points' => $request->way_points,
                ]);

                return $routes;
            });

            return $this->respondWithSuccess($updatedRoutes, 'Route updated successfully', 'API_ROUTE_UPDATED');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error occurred while updating route: ' . $th->getMessage());
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
