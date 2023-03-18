<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Financials;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\OrganizationType;
use App\Models\State;
use App\Models\Manager;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $organization_types = OrganizationType::get();
        $states = State::where('ctry_id', 167)->get();
        $org_dropdowns = Organization::get();
        // $managers_count = Manager::count();

        $organizations = Organization::with('manager', 'city', 'state', 'organizationType')
            ->orderBY('id', 'DESC')
            ->take(10)
            ->get();
        // dd($organizations->toArray());
        if ($request->has('filter')) {
            $organizations =  $this->filterManager($request);
        }

        return view('manager.index', [
            // 'managers_count' => $managers_count,
            'organization_types' => $organization_types,
            'org_dropdowns' => $org_dropdowns,
            'organizations' => $organizations,
            'states' => $states
        ]);
    }

    /**
     * [filter description]
     *
     * @return  [type]  [return description]
     */
    public function filterManager($request)
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
        $query = Organization::query();

        // Add organization ID constraint if provided
        if ($oId) {
            $query->where('id', $oId);
        }
        // Add date range constraint if both dates are provided
        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        } else {
            // If only one date is provided, add an equal constraint
            if ($from) {
                $query->where('created_at', '>=', $from);
            } elseif ($to) {
                $query->where('created_at', '<=', $to);
            }
        }
        // Execute the query
        $organizations = $query->with('manager', 'city', 'state', 'organizationType')
            ->get();
        // dd($organizations->toArray());
        return $organizations;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        dd('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'org_name' => 'required|string',
            'org_type ' => 'nullable|numeric',
            'org_email' => 'required|email',
            'org_phone' => 'required|regex:/^\d{11}$/',
            'org_state' => 'required|numeric',
            'org_city' => 'required|numeric',

            'org_head_name' => 'required|string',
            'org_head_phone' => 'required|regex:/^\d{11}$/',
            'org_head_email' => 'required|email',

            'man_name' => 'required|string',
            'man_email' => 'nullable|email',
            'man_phone' => 'required|unique:managers,phone',
            'man_pic' => 'nullable|mimes:jpg,png',

            'wallet' => 'required',
            'payment' => 'required',

            'org_amount' => 'nullable|numeric',
            'org_trail_start_date' => 'nullable|date',
            'org_trail_end_date' => 'nullable|date',

            'driver_amount' => 'nullable|numeric',
            'driver_trial_start_date' => 'nullable|date',
            'driver_trial_end_date' => 'nullable|date',

            'passenger_amount' => 'nullable|numeric',
            'passenger_trail_start_date' => 'nullable|date',
            'passenger_trail_end_date' => 'nullable|date',
        ]);

        $user = Auth::user();
        $error = false;
        DB::beginTransaction();
        $users = new User();
        $users->user_name    = $request->input('man_name');
        $users->email        = $request->input('man_email');
        // $users->user_type    = 'manager';
        // $users->otp          = rand(100000, 999999);
        $users->phone        = $request->input('man_phone');
        $users_save          = $users->save();
        if ($users_save) {
            $org = new Organization();
            $org->u_id          = $user->id;
            $org->name          = $request->input('org_name');
            $org->branch_name   = $request->input('org_branch_name');
            $org->branch_code   = $request->input('org_branch_code');
            $org->o_type_id     = $request->input('org_type');
            $org->email         = $request->input('org_email');
            $org->phone         = $request->input('org_phone');
            $org->address       = $request->input('org_address');
            $org->s_id          = $request->input('org_state');
            $org->c_id          = $request->input('org_city');
            $org->head_name     = $request->input('org_head_name');
            $org->head_phone    = $request->input('org_head_phone');
            $org->head_email    = $request->input('org_head_email');
            $org->head_address  = $request->input('org_head_address');
            $org_save = $org->save();
            if ($org_save) {
                $manager = new Manager();
                $manager->o_id          = $org->id;
                $manager->name          = $request->input('man_name');
                $manager->email         = $request->input('man_email');
                $manager->phone         = $request->input('man_phone');
                $manager->address       = $request->input('man_address');
                $manager->otp           = substr(uniqid(), -4);
                $manager->picture       = '';
                $manager_save = $manager->save();
                if ($manager_save) {
                    $financials = new Financials();
                    $financials->u_id                       = $user->id;
                    $financials->o_id                       = $org->id;

                    $financials->org_wallet                 = isset($request->wallet[0]) ? 1 : 0;
                    $financials->org_payment                = isset($request->payment[0]) ? 1 : 0;
                    $financials->org_amount                 = $request->input('org_amount');
                    $financials->org_trail_start_date       = $request->input('org_trail_start_date');
                    $financials->org_trail_end_date         = $request->input('org_trail_end_date');

                    $financials->driver_wallet              = isset($request->wallet[1]) ? 1 : 0;
                    $financials->driver_payment             = isset($request->payment[1]) ? 1 : 0;
                    $financials->driver_amount              = $request->input('driver_amount');
                    $financials->driver_trail_start_date    = $request->input('driver_trial_start_date');
                    $financials->driver_trail_end_date      = $request->input('driver_trial_end_date');

                    $financials->passenger_wallet           = isset($request->wallet[2]) ? 1 : 0;
                    $financials->passenger_payment          = isset($request->payment[2]) ? 1 : 0;
                    $financials->passenger_amount           = $request->input('passenger_amount');
                    $financials->passenger_trail_start_date = $request->input('passenger_trail_start_date');
                    $financials->passenger_trail_end_date   = $request->input('passenger_trail_end_date');

                    $financials_save = $financials->save();
                    if (!$financials_save) {
                        $error = true;
                    }
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
        } else {
            $error = true;
        }
        if (!$error) {
            DB::commit();
            return redirect()->route('manager.index')
                ->with('success', 'Organization created successfully.');
        } else {
            DB::rollback();
            return redirect()->route('manager.index')
                ->with('error', 'Error Occured while Organization Creation.');
        }
        return response();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Manager  $Manager
     * @return \Illuminate\Http\Response
     */
    public function show(Manager $Manager)
    {
        dd('show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Manager  $Manager
     * @return \Illuminate\Http\Response
     */
    public function edit(Manager $Manager)
    {
        dd('edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Manager  $Manager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Manager $Manager)
    {
        dd('edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Manager  $Manager
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manager $Manager)
    {
        dd('destroy');
    }

    /**
     * [deleteOrganization description]
     *
     * @param   [type]  $id  [$id description]
     *
     * @return  [type]       [return description]
     */
    public function deleteOrganization($id)
    {
        $delOrg = Organization::where('id', $id)->delete();
        $delMan = Manager::where('o_id', $id)->delete();
        // $delFin = Financials::where('o_id', $id)->delete();
        if ($delMan && $delOrg) {
            return response()->json([
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    /**
     * Display the log report for the organization.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function logReport(Request $request)
    {
        $request->validate([
            'o_id' => 'nullable|numeric',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after:from',
        ], [
            'to.after' => "The registration to date must be a date after registration from.",
        ]);

        $data = [];
        if ($request->isMethod('post')) {
            if ($request->has('filter')) {
                $data = $this->filterReport($request);
            }
        }
        $organizations = Organization::get();
        $org_dropdowns = $organizations;
        return view('manager.report.index', [
            'organizations' => $organizations,
            'org_dropdowns' => $org_dropdowns,
            'report_date' => $data
        ]);
    }

    protected function filterReport($request)
    {
        $query = Trip::query();

        // Add date range constraint if both dates are provided
        $query->when($request->input('from') && $request->input('to'), function ($query) use ($request) {
            $query->whereBetween('created_at', [$request->input('from'), $request->input('to')]);
        })->when($request->input('from') && !$request->input('to'), function ($query) use ($request) {
            $query->where('created_at', '>=', $request->input('from'));
        })->when(!$request->input('from') && $request->input('to'), function ($query) use ($request) {
            $query->where('created_at', '<=', $request->input('to'));
        });

        $trpis = $query->where('o_id', $request->o_id)
        ->with('organizations:id,name')
        ->with('routes:id,name,number,from,to')
        ->with('vehicles:id,number')
        ->with('drivers:id,name')
        ->get();
        dd($trpis->toArray());
        return $trpis;
    }

    /**
     * [awaitingApproval description]
     *
     * @return  [type]  [return description]
     */
    public function awaitingApproval()
    {
        $organizations = Organization::get();
        $org_dropdowns = $organizations;
        return view('manager.approval.awaiting_approvals', [
            'organizations' => $organizations,
            'org_dropdowns' => $org_dropdowns
        ]);
    }

    /**
     * [approvedUser description]
     *
     * @return  [type]  [return description]
     */
    public function approvedUser()
    {
        $organizations = Organization::get();
        return view('manager.approval.approved_user', [
            'organizations' => $organizations
        ]);
    }

    /**
     * [disapprovedUser description]
     *
     * @return  [type]  [return description]
     */
    public function disapprovedUser()
    {
        $organizations = Organization::get();
        return view('manager.approval.disapproved_user', [
            'organizations' => $organizations
        ]);
    }

    /**
     * [pastUser description]
     *
     * @return  [type]  [return description]
     */
    public function pastUser()
    {
        $organizations = Organization::get();
        return view('manager.approval.pastuser', [
            'organizations' => $organizations
        ]);
    }
}
