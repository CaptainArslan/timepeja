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
        return view('manager.index', compact('organizaton_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        dd($request->all());
        $this->validate($request, [
            'org_name' => 'required',
            'org_branch_name' => 'required|string',
            'org_branch_code' => 'required|string',
            'org_type' => 'required|int',
            'org_email' => 'required|email|string',
            'org_phone' => 'required|string',
            'org_address' => 'required|string',
            'org_head_name' => 'required|string',
            'org_head_email' => 'required|email',
            'org_head_phone' => 'required',
            'org_head_address' => '',
            'man_name' => 'required',
            'man_email' => 'required|email',
            'man_phone' => 'required',
            'man_pic' => '',
            'man_address' => 'required',
            'org_trail_days' => 'required',
            'org_start_date' => 'required|date',
            'org_end_date' => 'required|date',
        ]);


        $user = Auth::user();
        $error = false;
        $organization = new Organization();
        DB::beginTransaction();
        $organization->user_id = $user->id;
        $organization->name = $request->input('org_name');
        $organization->branch_name = $request->input('org_branch_name');
        $organization->branch_code  = $request->input('org_branch_code');
        $organization->org_type = $request->input('org_type');
        $organization->email  = $request->input('org_email');
        $organization->phone = $request->input('org_phone');
        $organization->address = $request->input('org_address');
        $organization->head_name = $request->input('org_head_name');
        $organization->head_email = $request->input('org_head_email');
        $organization->head_phone = $request->input('org_head_phone');
        $organization->head_address = $request->input('org_head_address');
        $org_save = $organization->save();
        if($org_save){
            Log::info(" Organization save = ".$org_save);
            $transportManager = new TransportManager();
            $transportManager->org_id = $organization->id;
            $transportManager->user_id =  $user->id;
            $transportManager->name =  $request->input('man_name');
            $transportManager->email =  $request->input('man_email');
            $transportManager->phone =  $request->input('man_phone');
            $transportManager->address =  $request->input('man_address');
            $transportManager->pic = '';
            $trans_save = $transportManager->save();
            if($trans_save){
                Log::info(" Transpot Manager save = ".$trans_save);
                $financials = new Financials();
                $financials->org_id  = $organization->id;
                $financials->user_id  = $user->id;
                $financials->org_trail_days  = $request->input('org_trail_days');
                $financials->org_start_date  = $request->input('org_start_date');
                $financials->org_end_date  = $request->input('org_end_date');
                $financials->manager_wallet  = $request->input('manager_wallet');
                $financials->driver_wallet  = $request->input('driver_wallet');
                $financials->passenger_wallet  = $request->input('passenger_wallet');
                $financials->manager_payment  = $request->input('manager_payment');
                $financials->manager_amount  = $request->input('manager_amount');
                $financials->manager_trail_end_date  = $request->input('manager_trail_end_date');
                $financials->driver_payment  = $request->input('driver_payment');
                $financials->driver_amount  = $request->input('driver_amount');
                $financials->driver_trail_end_date  = $request->input('driver_trail_end_date');
                $financials->passenger_payment  = $request->input('passenger_payment');
                $financials->passenger_amount  = $request->input('passenger_amount');
                $financials->passenger_trail_end_date  = $request->input('passenger_trail_end_date');
                $fin_save = $financials->save();
                if($fin_save){
                    $user = new User();
                    Log::info(" User save = ".$fin_save);
                    $user->name = $request->input('man_name');
                    $user->email =  $request->input('man_email');
                    $user->phone =  $request->input('man_phone');
                    $user->otp =  substr(uniqid(), -4);
                    $user->token =  Str::random(40);
                    $user->password = Hash::make('password');
                    $user_save = $user->save();
                    if(!$user_save){
                        $error = true;
                    }
                }else{
                    $error = true;
                    // DB::rollback();
                }
            }else{
                $error = true;
                // DB::rollback();
            }
        }else{
            $error = true;
            // DB::rollback();
        }

        if(!$error){
            DB::commit();
            return redirect()->route('manager.index')
            ->with('success', 'Manager created successfully.');
        }else{
            DB::rollback();
            return redirect()->route('manager.index')
            ->with('error', 'Error Occured while Manager Creation.');
        }
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
