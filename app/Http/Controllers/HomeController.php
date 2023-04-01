<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use App\Models\Schedule;
use App\Models\Vehicle;
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
}
