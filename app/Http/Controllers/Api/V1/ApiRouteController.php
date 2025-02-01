<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Route;
use Illuminate\Http\JsonResponse;

class ApiRouteController extends BaseController
{
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
}
