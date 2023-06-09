<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::all(); 
        return view('setting.all-setting',compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'google_api' => 'required'
        ]);

        try {
  
            /*------------------------------------------
            --------------------------------------------
            Start DB Transaction
            --------------------------------------------
            --------------------------------------------*/
            DB::beginTransaction();
  
            /* Create New User */
            $setting = Setting::create([
                    'r_id' => Auth::user()->id,
                    'credentials' => $request->google_api,
                    'status' => 0
                ]);
            if($setting)
            {
                DB::commit();
                return redirect(route('setting.index'))->with("success","Setting has been added");
            }else{
                return redirect()->back()->with("error","Sorry Error Occured !");
            }    
              
        } catch (Exception $e) {
  
            /*------------------------------------------
            --------------------------------------------
            Rollback Database Entry
            --------------------------------------------
            --------------------------------------------*/
            DB::rollback();
            throw $e;
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
        $settingEdit = Setting::find($id);
        return view('setting.all-setting',compact('settingEdit'));
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
        $updateSetting = Setting::find($id);
        $updateSetting->credentials = $request->google_api;
        $updateSetting->update();
        return redirect()->back()->with("success","Setting Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
