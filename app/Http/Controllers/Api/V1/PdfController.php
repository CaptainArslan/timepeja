<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pdf as ModelsPdf;
use Illuminate\Support\Facades\Validator;
use PDF;

class PdfController extends Controller
{

    /**
     * Log report pdf
     *
     * @param Request $request
     * @return void
     */
    function logReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'record_ids' => ['required', 'array'],
            'record_ids.*' => ['integer'],
        ], [
            'type.required' => 'Type is required',
            'type.string' => 'Type must be a string',
            'from.date' => 'From must be a date',
            'to.date' => 'To must be a date',
            'to.after_or_equal' => 'To must be after or equal to From',
            'record_ids.array' => 'Arr must be an array',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $query = Schedule::query();

            switch ($request->type) {
                case 'driver':
                    $query->whereIn('d_id', $request->record_ids);
                    break;
                case 'vehicle':
                    $query->whereIn('v_id', $request->record_ids);
                    break;
                case 'route':
                    $query->whereIn('route_id', $request->record_ids);
                    break;
                default:
                    break;
            }

            $query->when($request->filled('from') && $request->filled('to'), function ($query) use ($request) {
                $query->whereBetween('date', [$request->from, $request->to]);
            });

            $query->when($request->filled('from'), function ($query) use ($request) {
                $query->where('date', '>=', $request->from);
            });

            $query->when($request->filled('to'), function ($query) use ($request) {
                $query->where('date', '<=', $request->to);
            });

            $manager = auth('manager')->user();

            $result = $query->where('o_id', $manager->o_id)
                ->with('organizations:id,name,branch_name,branch_code,email,phone,address,code')
                ->with('routes:id,name,number,from,to')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->select('id', 'o_id', 'route_id', 'v_id', 'd_id', 'date', 'time as scheduled_time', 'start_time', 'end_time', 'is_delay', 'trip_status', 'delayed_reason')
                ->get();

            $data = [
                'report' => $result->toArray(),
                'request' => $request->all()
            ];

            $pdf = PDF::loadview('manager.report.export.logreport', $data);
            $pdf->setPaper('A4', 'landscape');

            $filename = date('Ymd_His') . '_Log_Report.pdf'; // Generate a unique filename
            $filePath = public_path('uploads/pdf/' . $filename); // Get the full file path

            $pdf->save($filePath); // Save the PDF to the specified folder

            $pdfModel = new ModelsPdf();
            $pdfModel->url = asset('/uploads/pdf/' . $filename);

            if ($pdfModel->save()) {
                return $this->respondWithSuccess($pdfModel, 'Pdf Created Successfully', 'LOG_REPORT_PDF_CREATED_SUCCESSFULLY');
            } else {
                // Delete the saved PDF file if model saving failed
                // if (file_exists($filePath)) {
                //     unlink($filePath);
                // }
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
