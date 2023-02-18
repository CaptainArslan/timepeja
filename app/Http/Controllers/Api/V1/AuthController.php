<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:5'],
            'phone' => ['required', 'numeric', 'min:11'],
            'otp' => ['required', 'numeric'],
            'password' => ['required', 'confirmed']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $res =  User::where([['phone', $request->phone], ['otp', $request->otp]])->first();
        if (empty($res)) {
            return $this->sendError('User not Exist', ['error' => 'Ask your Administration for registration'], 400);
        } else if (!empty($res->password)) {
            return $this->sendError('User Already Exist', ['error' => 'Manager Already registered Please Login'], 400);
        }

        $user = User::findOrFail($res->id);
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->save();
        $success['token'] = $user->createToken('ApiToken')->plainTextToken;
        $success['name'] = $user;
        return $this->sendResponse($success, 'Manager register successfully.');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'numeric', 'min:11'],
            'password' => ['required']
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }
        $user = User::where([['phone', $request->phone], ['user_type', 'manager']])->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError('Unauthorised', ['error' => 'Invalid phone or password'], 401);
        }
        $success['token'] = $user->createToken('ApiToken')->plainTextToken;
        $success['user'] = $user;
        return $this->sendResponse($success, 'User login successfully.');
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->sendResponse('success', 'Manager successfully logout');
    }

    public function change_password(Request $request)
    {
        return 'change Password';
    }

    public function get_code(Request $request)
    {
        return 'get_code';
    }

    public function forgot_password(Request $request)
    {
        return 'forgot password';
    }

    public function profile_update(Request $request, $user_id)
    {

        if (isset($user_id)) {
            $user = User::find($user_id);

            $user->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to update profile'
            ]);
        }
    }
}
