<?php

namespace App\Http\Controllers\Api\V1\Passenger;

use App\Models\Route;
use App\Models\Passenger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;

class RouteController extends BaseController
{

    public function getFavoriteRoute() {
        $passenger = auth('passenger')->user();
        $favoriteRoutes = $passenger->routes()->select('routes.id', 'name', 'number', 'from', 'to')->get();
        return $this->respondWithSuccess($favoriteRoutes, 'Favorite routes retrieved successfully', 'FAVORITE_ROUTES_RETRIEVED');
    }
    

    public function addFavoriteRoute(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'route_ids' => ['required', 'array'],
            'route_ids.*' => ['numeric', 'exists:routes,id'],
        ], [
            'route_ids.required' => 'Please provide route IDs',
            'route_ids.array' => 'Route IDs must be an array',
            'route_ids.*.numeric' => 'Route IDs must be numeric',
            'route_ids.*.exists' => 'Route IDs must be valid',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError(implode(', ', $validator->errors()->all()));
        }

        // Get the authenticated passenger using the JWT token
        $passenger = auth('passenger')->user();

        // Get the passenger's current favorite route IDs
        $currentFavorites = $passenger->routes()->pluck('routes.id')->toArray();

        // Filter the provided route IDs to exclude those already in favorites
        $newRouteIds = array_diff($request->route_ids, $currentFavorites);

        // Attach the new route IDs to the passenger's routes
        $passenger->routes()->attach($newRouteIds);

        if (count($newRouteIds) > 0) {
            return $this->respondWithSuccess(null, 'Route(s) added to favorites', 'ROUTE_ADDED_TO_FAVORITES');
        } else {
            return $this->respondWithError('Route(s) already in favorites');
        }
    }

    public function removeFavoriteRoute(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'route_ids' => ['required', 'array'],
            'route_ids.*' => ['numeric', 'exists:routes,id'],
        ], [
            'route_ids.required' => 'Please provide route IDs',
            'route_ids.array' => 'Route IDs must be an array',
            'route_ids.*.numeric' => 'Route IDs must be numeric',
            'route_ids.*.exists' => 'Route IDs must be valid',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError(implode(', ', $validator->errors()->all()));
        }

        // Get the authenticated passenger using the JWT token
        $passenger = auth('passenger')->user();

        // Get the passenger's current favorite route IDs
        $currentFavorites = $passenger->routes()->pluck('routes.id')->toArray();

        // Filter the provided route IDs to include only those currently in favorites
        $routeIdsToRemove = array_intersect($request->route_ids, $currentFavorites);

        if (count($routeIdsToRemove) > 0) {
            // Detach the route IDs to remove from the passenger's favoriteRoutes
            $passenger->routes()->detach($routeIdsToRemove);

            return $this->respondWithSuccess(null, 'Route(s) removed from favorites', 'ROUTE_REMOVED_FROM_FAVORITES');
        } else {
            return $this->respondWithError('Route(s) not found in favorites');
        }
    }

    public function getRoutes(Request $request)
    {
        try {
            $routes = Route::byOrganization($request->organization_id)->select('id', 'name')->get();
            return $this->respondWithSuccess($routes, 'Routes retrieved successfully', 'ROUTES_RETRIEVED');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error occured while retrieving routes');
        }
    }
}
