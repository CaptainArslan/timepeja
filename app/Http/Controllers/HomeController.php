<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use App\Models\Schedule;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tripCount = Schedule::where('trip_status', Schedule::TRIP_STATUS_COMPLETED)->count();
        $vehicleCount = Vehicle::where('status', Vehicle::STATUS_ACTIVE)->count();
        $passengerCount = Passenger::where('status', Passenger::STATUS_ACTIVE)->count();
        return view('dashboard.dashboard', [
            'tripCount' => $tripCount,
            'vehicleCount' => $vehicleCount,
            'passengerCount' => $passengerCount,
        ]);
    }
    // Auth Profile
    public function authProfile()
    {
        return view('auth.profile');
    }
    // changing auth credentials
    public function changeAuthInfo(Request $req)
    {
        $update_auth_info = User::where('email', auth()->user()->email)->update([
            'full_name' => $req->full_name,
            'user_name' => $req->full_name,
            'phone' => $req->phone,
            'email' => $req->email,
            'password' => Hash::make($req->password)
        ]);

        if($update_auth_info)
        {
            return redirect()->back()->with("success","Profile Updated Successfully");
        }
        else
        {
            return redirect()->back()->with("error","Sorry! Profile is npt Updated");
        }


        
    }
}
