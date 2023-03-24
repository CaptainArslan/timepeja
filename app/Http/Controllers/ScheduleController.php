<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Schedule;
use PDF;
use App\Models\Organization;
use App\Models\Route;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->create();
        // $organizations = Organization::get();
        // return view('manager.schedule.create', [
        //     'organizations' => $organizations
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizations = Organization::get();
        $org_dropdowns = $organizations;
        return view('manager.schedule.create', [
            'organizations' => $organizations,
            'org_dropdowns' => $org_dropdowns
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $schedule               = new Schedule();
        $schedule->o_id         = $request->organization;
        $schedule->u_id         = $user->id;
        $schedule->route_id     = isset($request->route_no) ? $request->route_no : 1;
        $schedule->v_id         = isset($request->vehicle) ? $request->vehicle : 1;
        $schedule->d_id         = isset($request->driver) ? $request->driver : 1;
        $schedule->date         = $request->date;
        $schedule->time         = $request->time;
        $schedule->status       = 'draft';
        $save = $schedule->save();
        if ($save) {
            $data = Schedule::where('id', $schedule->id)->with('organizations', 'routes', 'vehicles', 'drivers')->get();
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Schedule::find($id)->delete();
        if ($del) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    /**
     * [getSchedule description]
     *
     * @return  [type]  [return description]
     */
    public function getSchedule(Request $request)
    {
        $data = Schedule::where('o_id', $request->orgId)
            ->where('date', '=', $request->date)
            ->where('status', Schedule::STATUS_DRAFT)
            ->with('organizations', function ($query) {
                $query->select('id', 'name');
            })
            ->with('routes', function ($query) {
                $query->select('id', 'name', 'number', 'from', 'to');
            })
            ->with('vehicles', function ($query) {
                $query->select('id', 'number');
            })
            ->with('drivers', function ($query) {
                $query->select('id', 'name');
            })
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * [schedulePublished description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function schedulePublished(Request $request)
    {
        $schedules = [];
        if ($request->isMethod('post')) {
            if ($request->has('filter')) {
                $schedules = $this->filterSchedule($request);
            } elseif ($request->has('print')) {
                $schedules = $this->filterSchedule($request);
                // dd($schedules->toArray());
                return view('manager.schedule.report.index', compact('schedules'));
                $this->generatePdf('manager.schedule.report.index', $schedules);
                // $pdf = PDF::loadView('manager.schedule.report.publish_schedule', [
                //     'schedules' => $schedules->toArray()
                // ]);
                // return $pdf->download('report.pdf');
            } elseif ($request->has('modify')) {
                $this->publishDraftSchedule($request, Schedule::STATUS_DRAFT);
            }
        }
        $organizations = Organization::get();
        $org_dropdowns = $organizations;
        return view('manager.schedule.index', compact('org_dropdowns', 'organizations', 'schedules'));
    }

    /**
     * [filter description]
     *
     * @return  [type]  [return description]
     */
    public function filterSchedule(Request $request)
    {
        $request->validate([
            'o_id' => 'nullable|numeric',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after:from',
        ], [
            'to.after' => "The registration to date must be a date after registration from.",
        ]);

        // Start with base query
        $query = Schedule::query();

        // Add organization ID constraint if provided
        $query->when($request->input('o_id'), function ($query, $oId) {
            $query->where('o_id', $oId);
        });

        // Add date range constraint if both dates are provided
        $query->when($request->input('from') && $request->input('to'), function ($query) use ($request) {
            $query->whereBetween('date', [$request->input('from'), $request->input('to')]);
        })->when($request->input('from') && !$request->input('to'), function ($query) use ($request) {
            $query->where('date', '>=', $request->input('from'));
        })->when(!$request->input('from') && $request->input('to'), function ($query) use ($request) {
            $query->where('date', '<=', $request->input('to'));
        });

        // Execute the query
        $schedule = $query->with('organizations:id,name')
            ->with('routes:id,name,number,from,to')
            ->with('vehicles:id,number')
            ->with('drivers:id,name')
            ->where('status', Schedule::STATUS_PUBLISHED)
            ->get();

        return $schedule;
    }

    /**
     * [publish description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function publishDraftSchedule(Request $request, $status = Schedule::STATUS_PUBLISHED)
    {
        try {
            DB::transaction(function () use ($request, $status) {
                foreach ($request->schedule_ids as $schedule_id) {
                    $update = Schedule::where('id', $schedule_id)->update([
                        'status' => $status == Schedule::STATUS_DRAFT ? Schedule::STATUS_DRAFT : Schedule::STATUS_PUBLISHED
                    ]);
                    if (!$update) {
                        throw new \Exception('Error updating schedule.');
                    }
                }
            });
            return redirect()->route('schedule.create')
                ->with('success', 'Schedule updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('schedule.create')
                ->with('error', 'Error occurred while updating schedule.');
        }
    }

    /**
     * Get a list of drivers, vehicles, or routes based on organization ID and type.
     *
     * @param Illuminate\Http\Request $request The HTTP request object containing the organization ID and type.
     *
     * @return Illuminate\Http\JsonResponse The JSON response containing the list of drivers, vehicles, or routes.
     */

    public function getDriverVehicleRoute(Request $request)
    {
        $data = null;
        $type = $request->type;

        switch ($type) {
            case 'driver':
                $data = Driver::where('o_id', $request->o_id)->select('id', 'name')->get();
                break;
            case 'vehicle':
                $data = Vehicle::where('o_id', $request->o_id)->select('id', 'number as name')->get();
                break;
            case 'route':
                $data = Route::where('o_id', $request->o_id)->select('id', 'name')->get();
                break;
            default:
                return response()->json(['status' => 'error', 'message' => 'Invalid request type']);
        }

        if (!$data) {
            return response()->json(['status' => 'error', 'message' => 'No data found']);
        }

        return response()->json([
            'status' => 'success',
            'type' => $type,
            'data' => $data
        ]);
    }

    public function generatePdf($view, $data)
    {
        // Load the view and pass the data
        $html = View::make($view, $data)->render();
        return $html;
        // Create a new instance of Dompdf
        $pdf = new Dompdf();
        // Load the HTML content
        $pdf->loadHtml($html);
        // Render the PDF
        $pdf->render();
        // Output the generated PDF to the browser
        return $pdf->stream();
    }
}
