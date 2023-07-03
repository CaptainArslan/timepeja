<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Manager;
use App\Models\Schedule;
use App\Models\Financials;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\OrganizationType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ManagerStoreRequest;
use PDF;

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
        if ($request->has('filter')) {
            $organizations =  $this->filterManager($request);
        } else {
            $organizations = Organization::with('organizationType')
                ->with('city', function ($query) {
                    $query->select('id', 'name');
                })
                ->with('state', function ($query) {
                    $query->select('id', 'name');
                })
                ->with('manager', function ($query) {
                    $query->select('id', 'o_id', 'name', 'email', 'phone', 'otp', 'picture');
                })
                ->orderBY('id', 'DESC')
                ->take(10)
                ->get();
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
        $result = Organization::when($oId, function ($query, $oId) {
            return $query->where('id', $oId);
        })
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from, $to]);
            })
            ->when($from && !$to, function ($query) use ($from) {
                return $query->whereDate('created_at', '>=', $from);
            })
            ->when($to && !$from, function ($query) use ($to) {
                return $query->whereDate('created_at', '<=', $to);
            })
            // ->with('organizations', function ($query) {
            //     $query->select('id', 'name');
            // })
            ->with('manager', function ($query) {
                $query->select('id', 'o_id', 'name', 'email', 'phone', 'otp', 'picture', 'address', 'about', 'status', 'created_at');
            })
            ->with('city', function ($query) {
                $query->select('id', 'name');
            })
            ->with('state', function ($query) {
                $query->select('id', 'name');
            })
            ->with('organizationType', function ($query) {
                $query->select('id', 'name');
            })
            ->get();
        // dd($result->toArray());
        return $result;
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
    public function store(ManagerStoreRequest $request)
    {
        $user = Auth::user();
        $error = false;
        DB::beginTransaction();
        $otp = rand(1000, 9999);
        $orgEmail = $request->input('org_email');
        // $users = new User();
        // $users->user_name    = $request->input('man_name');
        // $users->email        = $request->input('man_email');
        // // $users->user_type    = 'manager';
        // // $users->otp          = rand(100000, 999999);
        // $users->phone        = $request->input('man_phone');
        // $users_save          = $users->save();
        // if ($users_save) {
        // } else {
        //     $error = true;
        // }
        $org = new Organization();
        $org->u_id          = $user->id;
        $org->name          = $request->input('org_name');
        $org->branch_name   = $request->input('org_branch_name');
        $org->branch_code   = $request->input('org_branch_code');
        $org->o_type_id     = $request->input('org_type');
        $org->email         = $orgEmail;
        $org->phone         = $request->input('org_phone');
        $org->address       = $request->input('org_address');
        $org->code          = substr(uniqid(), -8);
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
            $manager->u_id          = $user->id;
            $manager->name          = $request->input('man_name');
            $manager->email         = $request->input('man_email');
            $manager->phone         = $request->input('man_phone');
            $manager->address       = $request->input('man_address');
            $manager->otp           = $otp;
            $manager->picture       = ($request->file('man_pic'))
                ? uploadImage($request->file('man_pic'), 'managers/profiles/')
                : null;
            $manager_save = $manager->save();
            if ($manager_save) {
                $financials = new Financials();
                $financials->u_id                       = $user->id;
                $financials->o_id                       = $org->id;
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
        if (!$error) {
            DB::commit();

            emailsendingJob($orgEmail, $org);

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
            'o_id' => ['nullable', 'numeric'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after:from'],
        ], [
            'to.after' => "The registration to date must be a date after registration from.",
        ]);

        $reports = [];
        if ($request->isMethod('post')) {
            if ($request->has('filter')) {
                $reports = $this->filterReport($request);
            }
            if ($request->has('export')) {
                $reports = $this->filterReport($request);
                $data = [
                    'report' => $reports->toArray(),
                    'request' => $request->all()
                ];
                $pdf = PDF::loadview('manager.report.export.logreport', $data);
                $pdf->setPaper('A4', 'landscape');
                return $pdf->download(time() . 'history_report.pdf');
            }
        }
        $organizations = Organization::where('status', Organization::STATUS_ACTIVE)->get();
        $org_dropdowns = $organizations;
        return view('manager.report.index', [
            'organizations' => $organizations,
            'org_dropdowns' => $org_dropdowns,
            'reports' => $reports
        ]);
    }


    /**
     * This function is to filter record
     *
     * @param [type] $request
     * @return void
     */
    protected function filterReport($request)
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
            // ->with('organizations:id,name,branch_name,branch_code,email,phone,address,code')
            // ->with('routes:id,name,number,from,to')
            // ->with('vehicles:id,number')
            // ->with('drivers:id,name')
            // ->get();
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
     * [awaitingApproval description]
     *
     * @return  [type]  [return description]
     */
    public function awaitingApproval()
    {
        $organizations = Organization::where('status', Organization::STATUS_ACTIVE)->get();
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
        $organizations = Organization::where('status', Organization::STATUS_ACTIVE)->get();
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
        $organizations = Organization::where('status', Organization::STATUS_ACTIVE)->get();
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
        $organizations = Organization::where('status', Organization::STATUS_ACTIVE)->get();
        return view('manager.approval.pastuser', [
            'organizations' => $organizations
        ]);
    }
}
