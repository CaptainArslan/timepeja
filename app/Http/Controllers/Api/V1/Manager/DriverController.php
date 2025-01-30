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
use Illuminate\Support\Facades\Storage;
use App\Models\Pdf as ModelsPdf;

class DriverController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $driver = Driver::ByOrganization($manager->organization_id)
            // with('organization')
            ->search($request->search)
            ->latest()
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

        if ($request->cnic_front) {
            Storage::delete($driver->cnic_front);
        }

        if ($request->cnic_back) {
            Storage::delete($driver->cnic_back);
        }

        if ($request->license_front) {
            Storage::delete($driver->license_front);
        }

        if ($request->license_back) {
            Storage::delete($driver->license_back);
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

    public function createPdf(Request $request)
    {
        // try {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $drivers = Driver::where('organization_id', $manager->organization_id)
            ->with('organization')
            ->get();

        $data = [
            'drivers' => $drivers->toArray(),
            'request' => $request->except(['_token']) // Sanitize request data
        ];


        $pdf = PDF::loadview('pdf.driver', $data);
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
        // } catch (\Throwable $th) {
        //     return $this->respondWithError('Error occurred while creating the PDF: ' . $th->getMessage());
        // }
    }
}
