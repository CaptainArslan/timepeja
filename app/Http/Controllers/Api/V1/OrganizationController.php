<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Organization;

class OrganizationController extends BaseController
{
    /**
     * function to return the all organizations
     *
     * @return void
     */
    public function index()
    {
        $data = Organization::where('status', Organization::STATUS_ACTIVE)
            // ->select('id', 'name', 'branch_name', 'branch_code', 'email', 'address', 'c_id', 's_id')
            ->with('city:id,name')
            ->with('state:id,name')
            ->get();

        return $this->respondWithSuccess(
            $data,
            'All Organizations',
            'ALL_ORGANIZATIONS'
        );
    }

    /**
     * function to return the organization by id
     *
     * @param [type] $id
     * @return void
     */
    public function show($code)
    {
        $data = Organization::where('status', Organization::STATUS_ACTIVE)
            ->where('code', $code)
            // ->select('id', 'name', 'branch_name', 'branch_code', 'email', 'address', 'c_id', 's_id')
            ->with('city:id,name')
            ->with('state:id,name')
            ->first();

        return $this->respondWithSuccess(
            $data,
            'Organization',
            'ORGANIZATION'
        );
    }
}
