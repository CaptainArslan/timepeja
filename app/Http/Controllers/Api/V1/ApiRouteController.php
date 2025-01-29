<?php

namespace App\Http\Controllers\Api\V1;

use PDF;
use App\Models\Route;
use Illuminate\Http\Request;
use App\Models\Pdf as ModelsPdf;
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

    public function createPdf(Request $request)
    {
        try {
            $manager = auth('manager')->user();
            $routes = Route::where('o_id', $manager->o_id)
                ->with('organization:id,name,branch_name,branch_code,email,phone,address,code')
                ->get();
            $data = [
                'routes' => $routes->toArray(),
                'request' => $request->all()
            ];
            $pdf = PDF::loadview('pdf.route', $data);
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
        } catch (\Throwable $th) {
            // Delete the saved PDF file if an exception occurred
            // if (file_exists($filePath)) {
            //     unlink($filePath);
            // }
            return $this->respondWithError('Error occurred while creating the PDF: ' . $th->getMessage());
        }
    }
}
