<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Throwable;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\OrganizationAccountDeactivate;
use App\Mail\OrganizationAccountDeactivateCode;

class OrganizationController extends BaseController
{

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = Organization::where('status', Organization::STATUS_ACTIVE)
            ->has('manager')
            ->select('id', 'name', 'code')
            ->get();
        return $this->respondWithSuccess(
            $data,
            'All Organizations',
            'ALL_ORGANIZATIONS'
        );
    }


    /**
     * @param $code
     * @return JsonResponse
     */
    public function show($code): JsonResponse
    {
        $data = Organization::where('status', Organization::STATUS_ACTIVE)
            ->where('code', $code)
            ->has('manager')
            ->select('id', 'name', 'branch_name', 'branch_code', 'email', 'address', 'c_id', 's_id')
            ->with('manager')
            ->with('city:id,name')
            ->with('state:id,name')
            ->first();

        return $this->respondWithSuccess(
            $data,
            'Organization',
            'ORGANIZATION'
        );
    }


    /**
     * @return JsonResponse
     */
    public function deactivateCode(): JsonResponse
    {
        $manager = auth('manager')->user();
        $organization = Organization::findOrFail($manager->o_id);
        $organization->deactivate_code = $this->generateRandomSixDigitNumber();
        $organization->save();

        try {
            Mail::to($organization->email)->send(new OrganizationAccountDeactivateCode($organization));
        } catch (Throwable $th) {
            Log::error('Error Occurred while email sending to organization' . $th->getMessage());
        }

        return $this->respondWithSuccess(
            $organization->only('id', 'email', 'branch_code', 'branch_name', 'phone', 'code', 'deactivate_code'),
            'Organization deactivate code sent',
            'ORGANIZATION_DEACTIVATE_CODE'
        );
    }

    /**
     * Organization Account Deactivate
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deactivate(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'deactivate_code' => ['required', 'string', 'exists:organizations,deactivate_code'],
            ], [
                'deactivate_code.exists' => 'Invalid Deactivate Code'
            ]);
            if ($validator->fails()) {
                return $this->respondWithError(implode(',', $validator->errors()->all()));
            }

            $manager = auth('manager')->user();
            $organization = Organization::where('id', $manager->o_id)->where('deactivate_code', $request->deactivate_code)->first();

            if ($organization->deactivate_code != $request->deactivate_code) {
                return $this->respondWithError('Invalid Deactivate Code');
            }

            if ($organization->status == Organization::STATUS_DEACTIVE) {
                return $this->respondWithError('Account already deactivated');
            }

            $organization->status = Organization::STATUS_DEACTIVE;
            $organization->save();

            if ($manager->device_token) {
                notification('Account Deactivated', 'Your account has been deactivated', $manager->device_token);
            }

            // Soft delete the organization and its related entries
            // DB::transaction(function () use ($organization) {
            //     $organization->delete();
            // $organization->vehicles()->delete();
            // $organization->drivers()->delete();
            // $organization->routes()->delete();
            // // $organization->requests()->delete();
            // $organization->users()->delete();
            // $organization->locations()->delete();
            // $organization->manager()->delete();
            // $organization->schedules()->delete();
            // });


            Mail::to($organization->email)->send(new OrganizationAccountDeactivate($organization));
        } catch (Throwable $th) {
            Log::error('Error Occurred while email sending to organization' . $th->getMessage());
        }

        return $this->respondWithSuccess(
            [],
            'Organization Deactivated',
            'ORGANIZATION_DEACTIVATED'
        );
    }


    /**
     * @return string
     */
    private function generateRandomSixDigitNumber(): string
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
