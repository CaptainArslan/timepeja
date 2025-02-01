<?php

namespace App\Http\Controllers\Api\V1;

use Throwable;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\OrganizationAccountDeactivationRequest;

class OrganizationController extends BaseController
{
    public function index(): JsonResponse
    {
        $data = Organization::where('status', Organization::STATUS_ACTIVE)
            ->whereHas('manager')
            ->select(
                'id',
                'name',
                'branch_name',
                'branch_code',
                'code',
            )
            ->get();

        return $this->respondWithSuccess(
            $data,
            'All Organizations',
            'ALL_ORGANIZATIONS'
        );
    }

    public function show($code): JsonResponse
    {
        $data = Organization::where('status', Organization::STATUS_ACTIVE)
            ->where('code', $code)
            ->whereHas('manager')
            ->with('manager')
            ->select('id', 'name', 'branch_name', 'branch_code', 'email', 'address')
            ->first();

        return $this->respondWithSuccess(
            $data,
            'Organization',
            'ORGANIZATION'
        );
    }

    public function deactivateCode(): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $organization = $manager->organization;

        if ($organization->status == Organization::STATUS_DEACTIVE) {
            return $this->respondWithError('Account already deactivated');
        }

        $organization->update([
            'deactivate_code' => $this->generateRandomSixDigitNumber()
        ]);

        try {
            OrganizationAccountDeactivationRequest::dispatch($organization);
        } catch (Throwable $th) {
            Log::error('Error Occurred while sending deactivate code to organization' . $th->getMessage());
        }

        return $this->respondWithSuccess(
            $organization,
            'Organization deactivate code sent',
            'ORGANIZATION_DEACTIVATE_CODE'
        );
    }

    public function deactivate(Request $request): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $organization = Organization::where('id', $manager->organization_id)->where('deactivate_code', $request->deactivate_code)->first();

        if ($organization->deactivate_code != $request->deactivate_code) {
            return $this->respondWithError('Invalid Deactivate Code');
        }

        if ($organization->status == Organization::STATUS_DEACTIVE) {
            return $this->respondWithError('Account already deactivated');
        }

        $organization->update([
            'status' => Organization::STATUS_DEACTIVE,
            'deactivate_code' => null
        ]);

        if ($manager->device_token) {
            notification('Account Deactivated', 'Your account has been deactivated', $manager->device_token);
        }

        return $this->respondWithSuccess(
            [],
            'Organization Deactivated',
            'ORGANIZATION_DEACTIVATED'
        );
    }

    private function generateRandomSixDigitNumber(): string
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
