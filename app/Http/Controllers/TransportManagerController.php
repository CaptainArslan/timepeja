<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Financials;
use Illuminate\Support\Str;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\OrganizationType;
use App\Models\TransportManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TransportManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizaton_types = OrganizationType::all();
        return view('manager.manager_list', compact('organizaton_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizaton_types = OrganizationType::all();
        return view('manager.index', compact('organizaton_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
        $this->validate($request, [
            // 'org_name' => 'required|string',
            // 'org_branch_name' => 'required|string',
            // 'org_branch_code' => 'required|string',
            // 'org_type' => 'required|int',
            // 'org_email' => 'required|email|string',
            // 'org_phone' => 'required|string',
            // 'org_address' => 'required|string',
            // 'org_head_name' => 'required|string',
            // 'org_head_email' => 'required|email',
            // 'org_head_phone' => 'required',
            // 'org_head_address' => '',
            // 'man_name' => 'required',
            // 'man_email' => 'required|email',
            // 'man_phone' => 'required',
            // 'man_pic' => '',
            // 'man_address' => 'required',
            // 'org_trail_days' => 'required',
            // 'org_start_date' => 'required|date',
            // 'org_end_date' => 'required|date',


            "org_name" => 'required|string',
            "org_branch_code" => 'nullable|integer',
            "org_email" => 'required|email',
            "org_branch_name" => "required|string",
            "org_type" => 'required|integer',
            "org_phone" => 'nullable|string',
            "org_address" => 'string|nullable',
            "org_state" => "required|integer",
            "org_city" => "required|integer",
            "org_head_name" => 'required|string',
            "org_head_phone" => 'nullable',
            "org_head_email" => 'nullable|email',
            "org_head_address" => 'nullable|string',
            "man_name" => 'required|string',
            "man_phone" => 'required|regex:/(01)[0-9]{9}/',
            "man_email" => 'nullable|email',
            "man_pic" => 'nullable|mimes:jpg,png',
            "man_address" => 'nullable',
            "manager_wallet" => "manager_wallet",
            "payment" => "required",
            "org_amount" => 'required|integer',
            "org_trial_days" => 'required|integer',
            "org_trail_start_date" => 'required|date',
            "org_trail_end_date" => 'required|date',
            "driver_amount" => 'required|integer',
            "driver_trial_days" => 'required|integer',
            "driver_trial_start_date" => 'required|date',
            "driver_trial_end_date" => 'required|date',
            "passenger_amount" => "required|integer",
            "passenger_trial_days" => 'required|integer',
            "passenger_trail_start_date" => 'required|date',
            "passenger_trail_end_date" => 'required|date',
        ]);


        // $user = Auth::user();
        // DB::beginTransaction();
        // DB::rollback();
        // DB::commit();
        $error = false;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransportManager  $transportManager
     * @return \Illuminate\Http\Response
     */
    public function show(TransportManager $transportManager)
    {
        dd('show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TransportManager  $transportManager
     * @return \Illuminate\Http\Response
     */
    public function edit(TransportManager $transportManager)
    {
        dd('edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransportManager  $transportManager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransportManager $transportManager)
    {
        dd('edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransportManager  $transportManager
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransportManager $transportManager)
    {
        dd('destroy');
    }
}
