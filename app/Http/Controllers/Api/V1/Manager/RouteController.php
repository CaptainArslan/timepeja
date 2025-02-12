<?php

namespace App\Http\Controllers\Api\V1\Manager;

use PDF;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Manager\Route\CreateRouteRequest;
use App\Http\Requests\Manager\Route\UpdateRouteRequest;

class RouteController extends Controller
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
                ->get(); // Remove `->toArray()` to keep them as Eloquent models

            if ($routes->isEmpty() || $routes->count() < 2) {
                return $this->respondWithError('Route not found or insufficient routes');
            }

            $updatedRoutes = DB::transaction(function () use ($routes, $manager, $request) {
                $route1 = $routes[0]; // Now an Eloquent model
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
                    'from' => $request->to, // Reverse direction
                    'to' => $request->from, // Reverse direction
                    'status' => Route::STATUS_ACTIVE,
                    'way_points' => $request->way_points,
                ]);

                return [$route1, $route2];
            });

            DB::commit();

            return $this->respondWithSuccess($updatedRoutes, 'Route updated successfully', 'API_ROUTE_UPDATED');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondWithError('Error occurred while updating route: ' . $th->getMessage());
        }
    }


    public function destroy($id): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $route = Route::withTrashed()->find($id);

        if (!$route) {
            return $this->respondWithError('Route not found');
        }

        if ($route->organization_id != $manager->organization_id) {
            return $this->respondWithError('Route does not belong to your organization');
        }

        // Correct way to delete multiple routes
        Route::where('number', $route->number)
            ->ByOrganization($manager->organization_id)
            ->delete(); // Removed get()

        return $this->respondWithDelete('Route deleted successfully', 'API_ROUTE_DELETED');
    }

    public function createPdf(Request $request)
    {
        try {
            $manager = Auth::guard('manager')->user();

            if (!$manager) {
                return $this->respondWithError('Manager not found');
            }

            $routes = Route::ByOrganization($manager->organization_id)
                ->with('organization')
                ->get();

            $data = [
                'routes' => $routes->toArray(),
                'request' => $request->except(['_token'])
            ];

            $pdf = PDF::loadview('pdf.route', $data);
            $pdf->setPaper('A4', 'landscape');

            $filename = date('Ymd_His') . '_Driver_Report_' . $manager->id . '.pdf';
            $filePath = 'public/pdf/' . $filename;

            Storage::put($filePath, $pdf->output());

            $data = [
                'url' => asset(Storage::url($filePath)),
            ];

            return $this->respondWithSuccess($data, 'Pdf Created Successfully', 'LOG_REPORT_PDF_CREATED_SUCCESSFULLY');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error occurred while creating the PDF: ' . $th->getMessage());
        }
    }
}
