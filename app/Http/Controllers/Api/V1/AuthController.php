<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Manager;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $fields = $request->all();
        $validate = Validator::make($fields, [
            'name' => 'required|string|min:3|max:255',
            'phone' => 'required|numeric|digits:11',
            'otp' => 'required|string',
            'password' => 'required|alpha_num|between:8,255|confirmed',
            'password_confirmation' => 'required|alpha_num|between:8,255',
            'email' => 'nullable|email|max:255',
        ], [
            'name.required' => 'Name is required',
            'phone.required' => 'Phone is required',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'otp.required' => 'Verification code is required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validate->errors(),
            ], 422);
        }

        $manager = Manager::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->first();

        if (!$manager) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone or verification code'
            ], 401);
        }

        if (empty($manager->token) && empty($manager->password)) {
            $manager->update([
                'password' => Hash::make($request->password),
                'token' => Str::random(30),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Manager registered successfully',
                'code' => 'Registered_Success',
                'data' => $manager,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Manager already exists. Please login.',
            ], 401);
        }
    }

    public function login(Request $request)
    {
        $fields = $request->all();
        $validate = Validator::make($fields, [
            'phone' => 'required|numeric|digits:11',
            'password' => 'required|alpha_num|between:8,255',
        ], [
            'phone.required' => 'Phone is required',
            'password.required' => 'Password is required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'success' => false,
                'errors' => $validate->errors()->all()
            ], 401);
        }

        $manager = Manager::where('phone', $fields['phone'])->with('managerOrganization')->first();

        if (!empty($manager) && Hash::check($request->password, $manager->password)) {
            $manager->makeHidden(['password']);

            return response()->json([
                "success" => true,
                "message" => "Login Successfully",
                "code" => "Login_API",
                "data" => $manager
            ]);
        }

        return response()->json([
            'success' => false,
            'errors' => ['Invalid Phone or Password']
        ], 401);
    }

    public function getCode(Request $request)
    {
        $fields = $request->all();
        $validate = Validator::make($fields, [
            'phone' => 'required|numeric|digits:11',
        ], [
            'phone.required' => 'Phone is required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validate->errors(),
            ], 422);
        }

        $manager = Manager::where('phone', $fields['phone'])->first();
        if (!empty($manager)) {
            $manager = Manager::find($manager->id);
            $manager->otp = substr(uniqid(), -4);
            $save = $manager->save();
            if ($save) {
                return response()->json([
                    'success' => true,
                    'message' => 'Otp sent successfully',
                    'code' => 'Get_Otp',
                    'data' => $manager->otp,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'errors' => ['Error Occured while sending otp']
                ], 401);
            }
        }

        return response()->json([
            'success' => false,
            'errors' => ['Invalid Phone number']
        ], 401);
    }


    public function forgetPassword(Request $request)
    {
        $fields = $request->all();
        $validate = Validator::make($fields, [
            'phone' => 'required|numeric|digits:11',
            'otp' => 'required|string',
            'password' => 'required|alpha_num|between:8,255|confirmed',
            'password_confirmation' => 'required|alpha_num|between:8,255',
        ], [
            'phone.required' => 'Phone is required',
            'password.required' => 'Password is required',
            'otp.required' => 'Verification code is required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validate->errors(),
            ], 422);
        }

        $manager = Manager::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->with('managerOrganization')
            ->first();

        if (!$manager) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone or verification code'
            ], 401);
        }

        if (!empty($manager->password)) {
            $manager->makeHidden(['password']);
            $manager->update([
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated Successfully',
                'code' => 'Password_change',
                'data' => $manager,
            ], 200);
        }
    }
}
