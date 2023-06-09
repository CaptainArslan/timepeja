<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Schedule;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
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
    public function profile(Request $request, User $user)
    {
        return view('auth.profile');
    }

    public function updateProfile(Request $request, User $user)
    {
        $request->validate([
            'full_name' => ['required', 'string'],
            'phone' => ['required'],
            'email' => ['required', 'string', 'email'],
            'old_password' => ['nullable', 'string', Rule::requiredIf($request->filled('password')), function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The :attribute is incorrect.');
                }
            }],
            'password' => ['nullable', 'string', 'min:8', 'max:15', 'confirmed', Rule::requiredIf(function () use ($request) {
                return $request->filled('old_password');
            })],
        ], [
            'full_name.required' => 'The full name field is required.',
            'full_name.string' => 'The Full name must be a string.',
            'phone.required' => 'The phone field is required.',
            'email.required' => 'The email field is required.',
            'email.string' => 'The email must be a string.',
            'email.email' => 'Please enter a valid email address.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.max' => 'The password may not be greater than 15 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'old_password.string' => 'The current password must be a string.',
        ]);

        $userData = [
            'full_name' => $request->input('full_name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->input('password'));
        }

        if ($user->update($userData)) {
            return redirect()->route('profile')->with('success', 'Profile updated successfully.');
        }

        return redirect()->route('profile')->with('error', 'Error occurred while updating profile.');
    }
}
