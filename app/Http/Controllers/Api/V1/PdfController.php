<?php

namespace App\Http\Controllers\Api\V1;

use PDF;
use Throwable;
use App\Models\Schedule;
use App\Traits\UserRequest;
use Illuminate\Http\Request;
use App\Models\Pdf as ModelsPdf;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PdfController extends Controller
{
    use UserRequest;

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
                return $this->respondWithError('Error occurred while creating the PDF. Failed to save the model.');
            }
        } catch (\Throwable $th) {
            return $this->respondWithError('Error occurred while creating the PDF: ' . $th->getMessage());
        }
    }

    /**
     * Log report pdf
     *
     * @param Request $request
     * @return void
     */
    function userRequests(Request $request)
    {

        $validator = $this->validateStatus($request);

        if ($validator->fails()) {
            return $this->respondWithError(implode(", ", $validator->errors()->all()));
        }

        try {
            $manager = auth('manager')->user();
            $requests = $this->getUserRequestsByStatus($request->status, $manager->o_id);
            $organization = $manager->organization;

            $data = [
                'requests' => $requests,
                'request' => $request->all(),
                'organization' => $organization,
            ];

            $pdf = PDF::loadview('pdf.requests', $data);
            $pdf->setPaper('A4', 'landscape');

            $filename = date('Ymd_His') . $request->status . '_Report.pdf'; // Generate a unique filename
            $filePath = public_path('uploads/pdf/' . $filename); // Get the full file path

            $pdf->save($filePath); // Save the PDF to the specified folder

            $pdfModel = new ModelsPdf();
            $pdfModel->url = asset('/uploads/pdf/' . $filename);

            if ($pdfModel->save()) {
                return $this->respondWithSuccess($pdfModel, 'Pdf Created Successfully', 'USER_REQUEST_REPORT_PDF_CREATED_SUCCESSFULLY');
            } else {
                return $this->respondWithError('Error occurred while creating the PDF. Failed to save the model.');
            }
        } catch (\Throwable $th) {
            return $this->respondWithError('Error occurred while creating the PDF: ' . $th->getMessage());
        }
    }

    public function createdSchedule($date)
    {
        $validator = Validator::make(['date' => $date], [
            'date' => ['required', 'date'],
        ], [
            'date.required' => 'Date is required',
            'date.date' => 'Date must be a valid date',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $nextDate = date("Y-m-d", strtotime($date) + 86400);
            $manager = auth('manager')->user();
            $schedule = Schedule::where('o_id', $manager->o_id)
                ->where('date', '>=', $date)
                ->where('date', '<', $nextDate)
                ->where('status', Schedule::STATUS_DRAFT)
                ->with('routes:id,name,number,from,to')
                ->with('vehicles:id,number')
                ->with('drivers:id,name')
                ->with('organizations:id,name')
                ->select('id', 'o_id', 'route_id', 'v_id', 'd_id', 'date', 'time', 'status', 'trip_status')
                ->get();
            if ($schedule->isEmpty()) {
                return $this->respondWithError('No data found');
            }

            $data  = [
                'schedules' => $schedule->toArray(),
                'organization' => $manager->organization->toArray(),
                'date' => $date,
                'title' => 'Created',
            ];
            $download_url = $this->creatdPdf($data);
            $response = [
                'download_url' => $download_url,
                'data' => $schedule,
            ];
            return $this->respondWithSuccess($response, 'Created schedule fetched successfully', 'CREATED_SCHEDULE_FETCHED_SUCCESSFULLY');
        } catch (Throwable $th) {
            Log::error('Error occurred while creating the PDF' . $th->getMessage());
            return $this->respondWithError('Error occurred');
        }
    }

    public function creatdPdf($data)
    {
        $pdf = PDF::loadview('pdf.schedule', $data);
        $pdf->setPaper('A4', 'landscape');

        $filename = uniqid() . '_' . $data['title'] . '_schedule.pdf'; // Generate a unique filename
        $filePath = public_path('uploads/pdf/' . $filename); // Get the full file path

        $pdf->save($filePath); // Save the PDF to the specified folder

        $pdfModel = new ModelsPdf();
        $pdfModel->url = asset('/uploads/pdf/' . $filename);

        if ($pdfModel->save()) {
            return $pdfModel->url;
        } else {
            Log::error('Error occurred while creating the PDF. Failed to save the model.' . $data['title']);
            return null;
        }
    }

    public function publishedSchedule($date)
    {
        $validator = Validator::make(['date' => $date], [
            'date' => ['required', 'date'],
        ], [
            'date.required' => 'Date is required',
            'date.date' => 'Date must be a valid date',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        $nextDate = date("Y-m-d", strtotime($date) + 86400);
        $manager = auth('manager')->user();
        $schedule = Schedule::where('o_id', $manager->o_id)
            ->where('date', '>=', $date)
            ->where('date', '<', $nextDate)
            ->where('status', Schedule::STATUS_PUBLISHED)
            ->with('routes:id,name,number,from,to')
            ->with('vehicles:id,number')
            ->with('drivers:id,name')
            ->with('organizations:id,name')
            ->select('id', 'o_id', 'route_id', 'v_id', 'd_id', 'date', 'time', 'status', 'trip_status')
            ->get();
        if ($schedule->isEmpty()) {
            return $this->respondWithError('No data found');
        }
        try {
            $data = $this->creatdPdf([
                'schedules' => $schedule->toArray(),
                'organization' => $manager->organization->toArray(),
                'date' => $date,
                'title' => 'Published',
            ]);

            return $this->respondWithSuccess($data, 'Published schedule fetched successfully', 'PUBLISHED_SCHEDULE_FETCHED_SUCCESSFULLY');
        } catch (Throwable $th) {
            Log::error('Error occurred while creating the PDF PUBLISHED SCHEDULE: ' . $th->getMessage());
            return $this->respondWithError('Error occurred while creating PDF');
        }
    }
}
