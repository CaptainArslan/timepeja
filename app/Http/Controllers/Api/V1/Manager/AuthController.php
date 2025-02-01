<?php

namespace App\Http\Controllers\Api\V1\Manager;

use ApiHelper;
use App\Models\Otp;
use App\Models\Manager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Manager\Auth\GetCodeRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Manager\Auth\ForgetPasswordRequest;
use App\Http\Requests\Manager\Auth\LoginRequest as ManagerLoginRequest;
use App\Http\Requests\Manager\Auth\RegisterRequest as ManagerRegisterRequest;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            'auth:manager',
            [
                'except' => [
                    'login',
                    'register',
                    'getCode',
                    'forgetPassword',
                    'webLogin',
                ]
            ]
        );
    }

    public function register(ManagerRegisterRequest $request): JsonResponse
    {
        try {
            $date = now()->toDateString();

            $otp = Otp::where('phone', $request->phone)
                ->where('otp', $request->otp)
                ->whereDate('created_at', $date)
                ->first();

            if (!$otp) {
                return $this->respondWithError('Invalid phone number or verification code');
            }

            if ($otp->isActive() === false) {
                return $this->respondWithError('Verification code has expired');
            }

            $manager = Manager::where('phone', $request->phone)->first();

            if (!$manager) {
                return $this->respondWithError("Invalid phone number or verification code");
            }

            if ($manager->organization->isActive() === false) {
                return $this->respondWithError("please contact your organization admin to activate your account");
            }

            if (!empty($manager->password)) {
                return $this->respondWithError('Manager already exist. Please login.');
            }

            $manager->update([
                'password' => Hash::make($request->password),
                'status' => Manager::STATUS_ACTIVE,
            ]);

            $otp->update([
                'is_verified' => true,
                'verified_at' => now(),
            ]);

            return $this->respondWithSuccess($manager, 'Manager registered successfully', 'REGISTER_API_SUCCESS');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occurred while registering manager' . $th->getMessage());
        }
    }

    public function login(ManagerLoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only(['phone', 'password']);

            $manager = Manager::where('phone', $credentials['phone'])
                ->with('organization')
                ->first();

            if (!$manager) {
                return $this->respondWithError('Invalid phone number or password');
            }

            if ($manager->status !== Manager::STATUS_ACTIVE) {
                return $this->respondWithError('Account is not active');
            }

            if ($manager->organization->isActive() === false) {
                return $this->respondWithError("please contact your organization admin to activate your account");
            }

            if (!$token = Auth::guard('manager')->attempt($credentials)) {
                return $this->respondWithError('Invalid phone number or password');
            }

            if ($request->device_token && $request->device_type) {
                $manager->deviceTokens()->firstOrCreate(
                    [
                        'token' => $request->device_token,
                        'device_type' => $request->device_type
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }

            return $this->respondWithSuccess($manager, 'Login successfully', 'LOGIN_API_SUCCESS', [
                'content-type' => 'application/json',
                'authorization' => $token
            ]);
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occurred while login' . $th->getMessage());
        }
    }

    public function getCode(GetCodeRequest $request): JsonResponse
    {
        try {
            $manager = Manager::where('phone', $request->phone)->first();

            if (!$manager) {
                return $this->respondWithError('Phone number does not exist');
            }

            $otp = rand(1000, 9999);

            $oneTimePassword = Otp::updateOrCreate(
                ['phone' => $request->phone],
                [
                    'otp' => $otp,
                    'expires_at' => now()->addMinutes(5),
                ]
            );

            if (!$oneTimePassword) {
                return $this->respondWithError('Error Occured while sending otp');
            }

            return $this->respondWithSuccess($oneTimePassword, 'Otp Sent Successfully', 'API_GET_CODE');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while sending otp' . $th->getMessage());
        }
    }

    public function forgetPassword(ForgetPasswordRequest $request): JsonResponse
    {
        try {
            $date = now()->toDateString();

            $otp = Otp::where('phone', $request->phone)
                ->where('otp', $request->otp)
                ->whereDate('created_at', $date)
                ->first();

            if (!$otp) {
                return $this->respondWithError('Invalid phone number or verification code');
            }

            if ($otp->isActive() === false) {
                return $this->respondWithError('Verification code has expired');
            }

            $manager = Manager::where('phone', $request->phone)->first();

            if (!$manager) {
                return $this->respondWithError('invalid phone or verification code');
            }

            $update = $manager->update([
                'password' => Hash::make($request->password),
            ]);

            if (!$update) {
                return $this->respondWithError('Error Occured while updating password');
            }

            return $this->respondWithSuccess(null, 'Password Updated Successfully', 'PASSWORD_UPDATE');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while updating password');
        }
    }

    public function logout(): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        // Delete device tokens associated with the manager
        $manager->deviceTokens()->delete();

        // Log out the manager using the manager guard
        Auth::guard('manager')->logout();

        return $this->respondWithSuccess(null, 'Successfully logged out', 'API_LOGOUT');
    }

    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth('manager')->refresh());
    }

    public function webLogin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'numeric'],
            'password' => [
                'required',
                'string',
                'between:8,25',
            ],
        ], [
            'phone.required' => 'Phone number is required',
            'phone.numeric' => 'Phone number must be numeric',
            'phone.digits' => 'Phone number must be 11 digits',
            'password.required' => 'Password is required',
            'password.between' => 'Password must be between :min and :max characters',
            'password.regex' =>
            'The password must contain at least one uppercase letter, one lowercase letter,
            one number, and one special character.'
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }
        try {
            $credentials = $request->only(['phone', 'password']);

            if (!$token = auth('manager')->attempt($credentials)) {
                return $this->respondWithError('Invalid phone number or password');
            }

            $user = auth('manager')->user();
            if (!$user) {
                return $this->respondWithError('User not Found');
            }

            $user['token'] = $token;

            return $this->respondWithSuccess($user, 'Login successfully', 'LOGIN_API_SUCCESS', [
                'content-type' => 'application/json',
                'authorization' => $token
            ]);
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occurred while login');
        }
    }
}
