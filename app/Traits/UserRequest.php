<?php

namespace App\Traits;

use PDF;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Request as ModelsRequest;
use Illuminate\Support\Facades\Validator;

trait UserRequest
{

    public function validateStatus(Request $request)
    {
        return Validator::make($request->all(), [
            'status' => ['required', 'string', Rule::in([
                ModelsRequest::STATUS_APPROVED,
                ModelsRequest::STATUS_PENDING,
                ModelsRequest::STATUS_DISAPPROVED,
                ModelsRequest::STATUS_MEET_PERSONALLY,
            ])],
        ]);
    }


    public  function filterReport($request)
    {
        $query = Schedule::query();
        if (gettype($request->input('selection')) === "string") {
            $selection = explode(",", $request->input('selection'));
        } else {
            $selection = $request->input('selection');
        }

        switch ($request->type) {
            case 'driver':
                if ($selection[0] == 'all') {
                    $query->whereNotNull('d_id');
                } else {
                    $query->whereIn('d_id', $selection);
                }
                break;
            case 'vehicle':
                if ($selection[0] == 'all') {
                    $query->whereNotNull('v_id');
                } else {
                    $query->whereIn('v_id', $selection);
                }
                break;
            case 'route':
                if ($selection[0] == 'all') {
                    $query->whereNotNull('route_id');
                } else {
                    $query->whereIn('route_id', $selection);
                }
                break;
            default:
                break;
        }

        $query->when($request->filled('from') && $request->filled('to'), function ($query) use ($request) {
            $query->whereBetween('date', [$request->input('from'), $request->input('to')]);
        });

        $query->when($request->filled('from'), function ($query) use ($request) {
            $query->where('date', '>=', $request->input('from'));
        });

        $query->when($request->filled('to'), function ($query) use ($request) {
            $query->where('date', '<=', $request->input('to'));
        });

        $result = $query->where('o_id', $request->o_id)
            ->where('status', Schedule::STATUS_PUBLISHED)
            ->with('organizations:id,name,branch_name,branch_code,email,phone,address,code')
            ->with('routes:id,name,number,from,to')
            ->with('vehicles:id,number')
            ->with('drivers:id,name')
            ->select('id', 'o_id', 'route_id', 'v_id', 'd_id', 'date', 'time as scheduled_time', 'start_time', 'end_time', 'is_delay', 'trip_status', 'delayed_reason')
            ->orderby('trip_status', 'desc')
            ->get();

        return $result;
    }

    /**
     * Export user requests to pdf
     *
     * @param $request
     * @return mixed
     */
    public function exportUserRequestsToPDF($requests, $requestData)
    {
        $data = [
            'requests' => $requests,
            'request' => $requestData,
        ];

        $pdf = PDF::loadview('manager.report.export.user_requests', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download(time() . 'Approved_User.pdf');
    }

    public function getUserRequestsByStatus($status, $organizationId = null)
    {
        $query = ModelsRequest::where('status', $status)
            ->whereIn('type', [ModelsRequest::STUDENT, ModelsRequest::EMPLOYEE])
            ->with('organization:id,name,branch_name,branch_code,email,phone,address,code')
            ->withCount('childRequests')
            ->latest()
            ->take(10);

        // If organizationId is provided, add a filter by organization
        if ($organizationId !== null) {
            $query->where('organization_id', $organizationId);
        }

        $requests = $query->get();

        return $requests;
    }


    /**
     * Get user requests
     *
     * @param $request
     * @return mixed
     */
    public function filterUserRequest($request)
    {
        $query = ModelsRequest::query();
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->type == 'guardian') {
            $query->whereIn('type', [ModelsRequest::STUDENT_GUARDIAN, ModelsRequest::EMPLOYEE_GUARDIAN]);
        } else {
            $query->where('type', $request->type);
        }

        $query->when($request->filled('from') && $request->filled('to'), function ($query) use ($request) {
            $query->whereBetween('created_at', [$request->input('from'), $request->input('to')]);
        });

        $query->when($request->filled('from'), function ($query) use ($request) {
            $query->where('created_at', '>=', $request->input('from'));
        });

        $query->when($request->filled('to'), function ($query) use ($request) {
            $query->where('created_at', '<=', $request->input('to'));
        });

        $result = $query->where('organization_id', $request->o_id)
            ->with('organization:id,name,branch_name,branch_code,email,phone,address,code')
            ->with('city:id,name')
            ->withCount('childRequests')
            ->get();

        return $result;
    }
}
