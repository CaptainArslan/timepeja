<?php

namespace App\Http\Controllers\Api\V1;

use PDF;
use Throwable;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\Pdf as ModelsPdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LogReportController extends BaseController
{
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
            'type.string' => 'Type must be a string',
            'from.date' => 'Invalid date format',
            'to.date' => 'Invalid date format',
            'to.after_or_equal' => 'To date must be after or equal to from date',
            'record_ids.required' => 'Record ids are required',
            'record_ids.*.integer' => 'ID must be an integer',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $manager = Auth::guard('manager')->user();

            if (!$manager) {
                return $this->respondWithError('Manager not found');
            }

            $query = Schedule::query();

            switch ($request->type) {
                case 'driver':
                    $query->whereIn('driver_id', $request->record_ids);
                    break;
                case 'vehicle':
                    $query->whereIn('vehicle_id', $request->record_ids);
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


            $result = $query->where('organization_id', $manager->organization_id)
                ->where('status', Schedule::STATUS_PUBLISHED)
                ->with('organization:id,name,branch_name,branch_code,email,phone,address,code')
                ->with('route')
                ->with('vehicle:id,number')
                ->with('driver:id,name')
                // ->select('id', 'organization_id', 'route_id', 'v_id', 'd_id', 'date', 'time as scheduled_time', 'start_time', 'end_time', 'is_delay', 'trip_status', 'delayed_reason')
                ->orderby('trip_status', 'desc')
                ->get();

            $download_url = '';

            if ($result->isEmpty()) {
                return $this->respondWithError('No data found');
            }

            $download_url = $this->creatdPdf($request, $result->toArray());

            $data = [
                'logreport' => $result,
                'download_url' => $download_url
            ];

            return $this->respondWithSuccess($data, 'Log report fetched successfully', 'LOG_REPORT_FETCHED_SUCCESSFULLY');
        } catch (Throwable $th) {
            return $this->respondWithError($th->getMessage());
        }
    }

    public function creatdPdf(Request $request, array $data)
    {
        $data = [
            'report' => $data,
            'request' => $request->all()
        ];

        $pdf = PDF::loadview('pdf.logreport', $data);
        $pdf->setPaper('A4', 'landscape');

        $filename = date('Ymd_His') . '_history_report.pdf'; // Generate a unique filename
        $filePath = public_path('uploads/pdf/' . $filename); // Get the full file path

        $pdf->save($filePath); // Save the PDF to the specified folder

        return asset('/uploads/pdf/' . $filename);
    }
}
