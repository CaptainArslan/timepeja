<?php

namespace App\Http\Controllers\Api\V1\Manager;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Manager\Vehicle\StoreVehicleRequest;
use App\Http\Requests\Manager\Vehicle\UpdateVehicleRequest;
use PDF;

class VehicleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $vehicles = Vehicle::with('vehicleType:id,name')
            ->ByOrganization($manager->organization_id)
            ->search($request->search)
            ->latest()
            ->paginate(getPaginated($request->limit));

        return $this->respondWithSuccess($vehicles, 'Oganization All Vehicle', 'ORGANIZATION_VEHICLE');
    }

    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $vehicle = Vehicle::create([
            'organization_id' => $manager->organization_id,
            'vehicle_type_id' => $request->vehicle_type_id,
            'number' => $request->number,
            'front_pic' => $request->front_pic,
            'number_plate' => $request->number_plate,
            'status' => Vehicle::STATUS_ACTIVE
        ]);

        $vehicle->load('vehicleType:id,name');

        return $this->respondWithSuccess($vehicle, 'Vehicle created successfully', 'VEHICLE_CREATED');
    }

    public function show($id): JsonResponse
    {
        $vehicle = Vehicle::withTrashed()->findOrFail($id);

        if (!$vehicle) {
            return $this->respondWithError('Vehicle not found');
        }

        $vehicle->load('vehicleType:id,name');

        return $this->respondWithSuccess($vehicle, 'Get Vehicle', 'API_GET_VEHICLE');
    }

    public function update(UpdateVehicleRequest $request, $id): JsonResponse
    {
        $vehicle = Vehicle::withTrashed()->with('vehicleType:id,name')->findorFail($id);

        if (!$vehicle) {
            return $this->respondWithError('Vehicle not found');
        }

        if ($vehicle->front_pic) {
            Storage::delete($vehicle->front_pic);
        }

        if ($vehicle->number_plate) {
            Storage::delete($vehicle->number_plate);
        }

        $vehicle->update([
            'vehicle_type_id' => $request->vehicle_type_id,
            'number' => $request->number,
            'front_pic' => $request->front_pic,
            'number_plate' => $request->number_plate,
        ]);

        return $this->respondWithSuccess($vehicle, 'Vehicle updated successfully', 'VEHICLE_UPDATED');
    }

    public function destroy($id): JsonResponse
    {
        $vehicle = Vehicle::withTrashed()->findOrFail($id);

        if (!$vehicle) {
            return $this->respondWithError('Vehicle not found');
        }

        $vehicle->delete();
        return $this->respondWithSuccess(null, 'Vehicle deleted successfully', 'VEHICLE_DELETED');
    }

    public function createPdf(Request $request)
    {
        try {
            $manager = Auth::guard('manager')->user();

            if (!$manager) {
                return $this->respondWithError('Manager not found');
            }

            $vehicles = Vehicle::ByOrganization($manager->organization_id)
                ->with('organization')
                ->with('vehicleType:id,name')
                ->get();

            $data = [
                'vehicles' => $vehicles->toArray(),
                'request' => $request->except(['_token'])
            ];

            $pdf = PDF::loadview('pdf.vehicle', $data);
            $pdf->setPaper('A4', 'landscape');

            $filename = date('Ymd_His') . '_Vehicle_Report_' . $manager->id . '.pdf';
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
