<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class SettingController extends Controller
{
    public function updateOrCreate(Request $request)
    {
        if ($request->isMethod('put')) {
            $request->validate([
                'credentials' => ['required', 'string'],
            ], [
                'credentials.required' => 'Please enter Google API Credentials.',
                'credentials.string' => 'Only strings are allowed for the credentials.',
            ]);

            $settings = Setting::updateOrCreate(
                ['u_id' => auth()->user()->id], // Search attributes
                [
                    'credentials' => $request->input('credentials'), // Values to update or create
                    'platform' => 'google_map',
                ]
            );

            if ($settings) {
                return Redirect::route('setting.google')->with('success', 'Settings updated successfully.')->with('settings', $settings);
            } else {
                return Redirect::route('setting.google')->with('error', 'Error occurred while updating settings.')->with('settings', $settings);
            }
        }

        $settings = Setting::where('platform', 'google_map')->first();
        return view('setting.google', compact('settings'));
    }
}
