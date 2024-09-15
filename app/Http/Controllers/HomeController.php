<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Schedule;
use App\Models\Passenger;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $schedule = Schedule::with(['route', 'driver'])->first();
        return view('dashboard.dashboard', get_defined_vars());
    }

    // Auth Profile
    public function profile()
    {
        return view('auth.profile');
    }

    public function updateProfile(Request $request, User $user)
    {
        // return $request;
        $request->validate([
            'full_name' => ['required', 'string'],
            'phone' => ['required'],
            'email' => ['required', 'string', 'email'],
            'old_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'max:15', 'confirmed'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ], [
            'full_name.required' => 'The full name field is required.',
            'full_name.string' => 'The full name must be a string.',
            'phone.required' => 'The phone field is required.',
            'email.required' => 'The email field is required.',
            'email.string' => 'The email must be a string.',
            'email.email' => 'Please enter a valid email address.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.max' => 'The password may not be greater than 15 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'old_password.string' => 'The current password must be a string.',
            'profile_image.image' => 'Profile must be an image',
            'profile_image.mimes' => 'Profile must be a file of type: jpeg, png, jpg, gif, svg',
            'profile_image.max' => 'Profile may not be greater than 2048 kilobytes',
        ]);

        if ($request->hasFile('profile_image')) {
            removeImage(Auth::user()->image, 'managers/profiles');
        }

        $image = ($request->file('profile_image')) ?
            uploadImage($request->file('profile_image'), 'managers/profiles', 'manager_profile')
            : Auth::user()->image;

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->input('password'));
        }

        $userData = [
            'full_name' => $request->input('full_name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'image' => $image,
        ];

        if ($user->where('id', Auth::user()->id)->update($userData)) {
            return redirect()->route('profile')->with('success', 'Profile updated successfully.');
        }

        return redirect()->route('profile')->with('error', 'Error occurred while updating profile.');
    }
}
