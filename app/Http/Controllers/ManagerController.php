<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use App\Models\Financials;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\OrganizationType;
use App\Models\State;
use App\Models\Manager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organization_types = OrganizationType::get();
        $managers = Manager::with('organizations')->get();
        $managers_count = Manager::count();
        $states = State::where('country_id', 167)->get();
        // return view('manager.create', compact('organization_types', 'managers', 'managers_count', 'users', 'states'));
        return view('manager.index', ['organization_types' => $organization_types, 'managers' => $managers, 'managers_count' => $managers_count, 'states'=> $states]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizaton_types = OrganizationType::all();
        $managers = Manager::all();
        $users = User::take(5)->orderby('id', 'desc')->get();
        $states = State::where('country_id', 167)->get();
        return view('manager.create', compact('organizaton_types', 'managers', 'users', 'states'));
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
            "org_name" => 'required|string',
            "org_branch_code" => 'nullable|numeric',
            "org_email" => 'required|email',
            "org_branch_name" => "required|string",
            "org_type" => 'required|numeric',
            "org_phone" => 'nullable|string',
            "org_address" => 'string|nullable',
            "org_state" => "required|numeric",
            "org_city" => "required|numeric",
            "org_head_name" => 'required|string',
            "org_head_phone" => 'nullable',
            "org_head_email" => 'nullable|email',
            "org_head_address" => 'nullable|string',
            "man_name" => 'required|string',
            "phone" => 'required|unique:users',
            "man_email" => 'nullable|email',
            "man_pic" => 'nullable|mimes:jpg,png',
            "man_address" => 'nullable',
            "manager_wallet" => "nullable",
            "driver_wallet" => "nullable",
            "passenger_wallet" => "nullable",
            "org_payment" => "nullable",
            "driver_payment" => "nullable",
            "passenger_payment" => "nullable",
            "org_amount" => 'nullable|numeric',
            "org_trial_days" => 'nullable|numeric',
            "org_trail_start_date" => 'nullable|date',
            "org_trail_end_date" => 'nullable|date',
            "driver_amount" => 'nullable|numeric',
            "driver_trial_days" => 'nullable|numeric',
            "driver_trial_start_date" => 'nullable|date',
            "driver_trial_end_date" => 'nullable|date',
            "passenger_amount" => "nullable|numeric",
            "passenger_trial_days" => 'nullable|numeric',
            "passenger_trail_start_date" => 'nullable|date',
            "passenger_trail_end_date" => 'nullable|date',
        ]);

        $user = Auth::user();
        $error = false;
        DB::beginTransaction();
        $users = new User();
        $users->name         = $request->input('man_name');
        $users->email        = $request->input('man_email');
        $users->user_type    = 'manager';
        $users->otp          = rand(100000, 999999);
        $users->phone        = $request->input('phone');
        $users_save          = $users->save();
        if ($users_save) {
            $org = new Organization();
            $org->user_id        = $user->id;
            $org->name          = $request->input('org_name');
            $org->branch_name   = $request->input('org_branch_name');
            $org->branch_code   = $request->input('org_branch_code');
            $org->org_type      = $request->input('org_type');
            $org->email         = $request->input('org_email');
            $org->phone         = $request->input('org_phone');
            $org->address       = $request->input('org_address');
            $org->state         = $request->input('org_state');
            $org->city          = $request->input('org_city');
            $org->head_name     = $request->input('org_head_name');
            $org->head_phone    = $request->input('org_head_phone');
            $org->head_email    = $request->input('org_head_email');
            $org->head_address  = $request->input('org_head_address');
            $org_save = $org->save();
            if ($org_save) {
                $manager = new Manager();
                $manager->user_id       = $user->id;
                $manager->org_id        = $org->id;
                $manager->name          = $request->input('man_name');
                $manager->email         = $request->input('man_email');
                $manager->phone         = $request->input('phone');
                $manager->address       = $request->input('man_address');
                $manager->pic           = '';
                $manager_save = $manager->save();
                if ($manager_save) {
                    $financials = new Financials();
                    $financials->user_id                    = $user->id;
                    $financials->org_id                     = $org->id;

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
}
