<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Schedule;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pdf as ModelsPdf;

class LogReportController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'record_ids' => ['required'],
            'record_ids.*' => ['integer'],
        ], [
            'type.required' => 'Type is required',
            'type.string' => 'Type must be string',
            'from.date' => 'From must be date',
            'to.date' => 'To must be date',
            'to.after_or_equal' => 'To must be after or equal from',
            'record_ids.array' => 'Arr must be array',
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
                ->where('status', Schedule::STATUS_PUBLISHED)
                ->with('organizations:id,name,branch_name,branch_code,email,phone,address,code')
                ->with('routes:id,name,number,from,from_longitude,from_latitude,to,to_latitude,to_longitude')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->select('id', 'o_id', 'route_id', 'v_id', 'd_id', 'date', 'time as scheduled_time', 'start_time', 'end_time', 'is_delay', 'trip_status', 'delayed_reason')
                ->orderby('trip_status', 'desc')
                ->get();


            $download_url = '';

            if ($result->isNotEmpty()) {
                $download_url = $this->creatdPdf($request, $result);
                $data = [
                    'logreport' => $result,
                    'download_url' => $download_url
                ];

                return $this->respondWithSuccess($data, 'Log report fetched successfully', 'LOG_REPORT_FETCHED_SUCCESSFULLY');
            }
            else{
                return $this->respondWithError('No data found');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function creatdPdf(Request $request, $data)
    {
        $data = [
            'report' => $data->toArray(),
            'request' => $request->all()
        ];

        $pdf = PDF::loadview('manager.report.export.logreport', $data);
        $pdf->setPaper('A4', 'landscape');

        $filename = date('Ymd_His') . '_history_report.pdf'; // Generate a unique filename
        $filePath = public_path('uploads/pdf/' . $filename); // Get the full file path

        $pdf->save($filePath); // Save the PDF to the specified folder

        $pdfModel = new ModelsPdf();
        $pdfModel->url = asset('/uploads/pdf/' . $filename);

        if ($pdfModel->save()) {
            return $pdfModel->url;
            // return $this->respondWithSuccess($pdfModel, 'Pdf Created Successfully', 'LOG_REPORT_PDF_CREATED_SUCCESSFULLY');
        } else {
            // Delete the saved PDF file if model saving failed
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            return null;
            // return $this->respondWithError('Error occurred while creating the PDF. Failed to save the model.');
        }
    }
}
