<?php

namespace App\Http\Controllers\Api\V1\Manager;

use App\Models\Driver;
use App\Events\SendSmsEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Events\AccountDeletedEvent;
use App\Events\FcmNotificationEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Manager\Driver\DriverCreateRequest;
use App\Http\Requests\Manager\Driver\DriverupdateRequest;

class DriverController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $driver = Driver::with('organization')
            ->ByCompany($manager->organization_id)
            ->search($request->search)
            ->paginate(getPaginated($request->limit));

        return $this->respondWithSuccess($driver, 'Oganization All Driver', 'ORGANIZATION_DRIVER');
    }

    public function store(DriverCreateRequest $request): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $driver = Driver::create([
            'organization_id' => $manager->organization_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'cnic' => $request->cnic,
            'cnic_front' => $request->cnic_front,
            'cnic_back' => $request->cnic_back,
            'license_no' => $request->license_no,
            'license_front' => $request->license_front,
            'license_back' => $request->license_back,
        ]);

        SendSmsEvent::dispatch($driver->phone);

        $driver->load('organization');

        return $this->respondWithSuccess($driver, 'Driver Creadted Successfully', 'DRIVER_CREATED');
    }

    public function show($id): JsonResponse
    {
        $driver = Driver::with('organization')->withTrashed()->findOrFail($id);

        if (!$driver) {
            return $this->respondWithError('Driver not found');
        }

        $driver->load('organization');

        return $this->respondWithSuccess($driver, 'Get Driver', 'API_GET_DRIVER');
    }

    public function update(DriverupdateRequest $request, $id): JsonResponse
    {
        $driver = Driver::with('organization')->withTrashed()->findOrFail($id);

        if (!$driver) {
            return $this->respondWithError('Driver not found');
        }

        $driver->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'cnic' => $request->cnic,
            'cnic_front' => $request->cnic_front,
            'cnic_back' => $request->cnic_back,
            'license_no' => $request->license_no,
            'license_front' => $request->license_front,
            'license_back' => $request->license_back,
        ]);

        FcmNotificationEvent::dispatch(
            $driver->deviceTokens()->pluck('token')->toArray(),
            'Driver Updated',
            'Your account has been updated by your organization manager'
        );

        return $this->respondWithSuccess($driver, 'Driver updated successfully', 'API_DRIVER_UPDATED');
    }

    public function destroy($id): JsonResponse
    {
        $driver = Driver::withTrashed()->findOrFail($id);

        if (!$driver) {
            return $this->respondWithError('Driver not found');
        }

        AccountDeletedEvent::dispatch($driver->phone);
        $driver->delete();

        return $this->respondWithSuccess(null, 'Driver deleted successfully', 'API_DRIVER_DELETED');
    }
}
