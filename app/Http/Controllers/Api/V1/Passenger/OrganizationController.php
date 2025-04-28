<?php

namespace App\Http\Controllers\Api\V1\Passenger;

use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all organizations
        $organizations = Organization::select()
            ->where('status', Organization::STATUS_ACTIVE)
            ->select(
                'id',
                'name',
                'code',
            )
            ->get();

        // Return the organizations as a JSON response
        return $this->respondWithSuccess(
            $organizations,
            'All Organizations List',
            'ALL_ORGANIZATIONS'
        );
    }

    public function show(Request $request, $code)
    {
        // Fetch the organization by ID
        $organization = Organization::select()
            ->where('status', Organization::STATUS_ACTIVE)
            ->where('code', $code)
            ->with([
                'manager',
                'state',
                'city',
                'organizationType',
            ])
            ->first();

        // Check if the organization exists
        if (!$organization) {
            return $this->respondWithError(
                'Organization not found',
            );
        }

        // Return the organization as a JSON response
        return $this->respondWithSuccess(
            $organization,
            'Organization Details',
            'ORGANIZATION_DETAILS'
        );
    }
}
