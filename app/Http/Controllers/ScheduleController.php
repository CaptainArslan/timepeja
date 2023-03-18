<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use PDF;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::get();
        return view('manager.schedule.create', [
            'organizations' => $organizations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizations = Organization::get();
        return view('manager.schedule.create', [
            'organizations' => $organizations
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
            ->where('status', 'draft')
            ->with('organizations', 'routes', 'vehicles', 'drivers')
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
        if (isset($_POST['filter'])) {
            $schedules = $this->filterSchedule($request);
            // dd($schedules->toArray());
        } elseif ($request->has('print')) {
            // dd($request->all())
            $schedules = $this->filterSchedule($request);
            // return view('welcome');
            $pdf = PDF::loadView('manager.schedule.report.publish_schedule', $schedules->toArray());
            return $pdf->download('report.pdf');
        }
        $organizations = Organization::get();
        $org_dropdowns = $organizations;
        return view('manager.schedule.published_schedule', [
            'org_dropdowns' => $org_dropdowns,
            'organizations' => $organizations,
            'schedules' => $schedules
        ]);
    }

    /**
     * [filter description]
     *
     * @return  [type]  [return description]
     */
    public function filterSchedule($request)
    {
        $request->validate(
            [
                'o_id'           => 'nullable|numeric',
                'from'           => 'nullable|date',
                'to'             => 'nullable|date|after:from',
            ],
            [
                'to.after' => "The registration to date must be a date after registration from.",
            ]
        );
        // Retrieve user input
        $oId = $request->input('o_id');
        $from = $request->input('from');
        $to = $request->input('to');

        // Start with base query
        $query = Schedule::query();

        // Add organization ID constraint if provided
        if ($oId) {
            $query->where('o_id', $oId);
        }
        // Add date range constraint if both dates are provided
        if ($from && $to) {
            $query->whereBetween('date', [$from, $to]);
        } else {
            // If only one date is provided, add an equal constraint
            if ($from) {
                $query->where('date', '>=', $from);
            } elseif ($to) {
                $query->where('date', '<=', $to);
            }
        }
        // Execute the query
        $organizations = $query->with('organizations', function ($query) {
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
        // dd($organizations->toArray());
        return $organizations;
    }

    /**
     * [publish description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function publish(Request $request)
    {
        DB::beginTransaction();
        $error = false;
        foreach ($request->schedule_ids as $schedule_id) {
            $update = Schedule::where('id', $schedule_id)->update([
                'status' => 'published'
            ]);
            if (!$update) {
                $error = true;
                break;
            }
        }
        if (!$error) {
            DB::commit();
            return redirect()->route('schedule.create')
                ->with('success', 'Schedule Published Successfully.');
        } else {
            DB::rollBack();
            return redirect()->route('schedule.create')
                ->with('error', 'Error occured while schedule publishing.');
        }
    }
}
