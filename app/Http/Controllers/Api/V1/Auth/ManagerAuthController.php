<?php

namespace App\Http\Controllers\Api\V1\Auth;

use ApiHelper;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;
use Throwable;

class ManagerAuthController extends BaseController
{

    public function __construct()
    {
        $this->middleware(
            'auth:manager',
            [
                'except' => [
                    'login',
                    'register',
                    'getVerificationCode',
                    'forgetPassword',
                    'webLogin',
                    'profile'
                ]
            ]
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'phone' => ['required', 'numeric'],
            'otp' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'between:8,25',
            ],
            'password_confirmation' => ['required', 'string', 'between:8,25'],
            'email' => ['nullable', 'email', 'max:255'],
        ], [
            'name.required' => 'Name is required',
            'phone.required' => 'Phone number is required',
            'phone.numeric' => 'Phone number must be numeric',
            'phone.digits' => 'Phone number must be 11 digits',
            'email.email' => 'Email must be a valid email address',
            'password.required' => 'Password is required',
            'password.between' => 'Password must be between :min and :max characters',
            'password.confirmed' => 'Password confirmation does not match',
            'password.regex' =>
            'The password must contain at least one uppercase letter, one lowercase letter, one number,
            and one special character.',
            'otp.required' => 'Verification code is required',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }
        try {
            $manager = Manager::where('phone', $request->phone)
                ->where('otp', $request->otp)
                ->first();

            if (!$manager) {
                return $this->respondWithError("Invalid phone number or verification code");
            }

            if ($manager->organization->status !== 1) {
                return $this->respondWithError("Organization is not active");
            }

            if (empty($manager->password)) {
                $manager::where('phone', $request->phone)->update([
                    'password' => Hash::make($request->password),
                    'status' => Manager::STATUS_ACTIVE,
                ]);
                return $this->respondWithSuccess($manager, 'Manager registered successfully', 'REGISTER_API_SUCCESS');
            } else {
                return $this->respondWithError('Manager already exist. Please login.');
            }
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occurred while registering manager');
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function login(Request $request): JsonResponse
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

            $user = Manager::where('phone', $credentials['phone'])
                ->with('organization')
                ->with('organization.city:id,name')
                ->with('organization.state:id,name')
                ->first();

            if (!$user) {
                return $this->respondWithError('Invalid phone number or password');
            }

            if ($user->status !== Manager::STATUS_ACTIVE) {
                return $this->respondWithError('Account is not active');
            }

            ApiHelper::saveDeviceToken($request, $user);

            if (!$token = auth('manager')->attempt($credentials)) {
                return $this->respondWithError('Invalid phone number or password');
            }

            return $this->respondWithSuccess($user, 'Login successfully', 'LOGIN_API_SUCCESS', [
                'content-type' => 'application/json',
                'authorization' => $token
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getVerificationCode(Request $request): JsonResponse
    {
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'phone' => ['required', 'numeric'],
        ], [
            'phone.required' => 'Phone number is required',
            'phone.numeric' => 'Phone number must be numeric',
            'phone.digits' => 'Phone number must be 11 digits',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $manager = Manager::where('phone', $fields['phone'])->first();
            if (!empty($manager)) {
                $otp = rand(1000, 9999);
                $manager->otp = $otp;
                $save = $manager->save();
                if ($save) {
                    $data = $manager->only('id', 'name', 'phone', 'otp');
                    if ($manager->device_token) {
                        notification('Otp', 'Your verification code is ' . $otp, $manager->device_token);
                    }
                    return $this->respondWithSuccess($data, 'Otp Sent Successfully', 'API_GET_CODE');
                } else {
                    return $this->respondWithError('Error Occured while sending otp');
                }
            } else {
                return $this->respondWithError('Invalid Phone number provided');
            }
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while sending otp');
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function forgetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'otp' => ['required', 'string'],
            'phone' => ['required', 'numeric'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'between:8,25',
            ],
        ], [
            'phone.required' => 'Phone is required',
            'password.required' => 'Password is required',
            'otp.required' => 'Verification code is required',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $manager = Manager::where('phone', $request->phone)
                ->where('otp', $request->otp)
                ->first();

            if (!$manager) {
                return $this->respondWithError('invalid phone or verification code');
            }

            $manager->password = Hash::make($request->password);
            $save = $manager->save();
            if (!$save) {
                return $this->respondWithError('Error Occured while updating password');
            }
            return $this->respondWithSuccess($manager, 'Password Updated Successfully', 'PASSWORD_UPDATE');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while updating password');
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            $data = $this->respondWithSuccess(
                auth('manager')->user()->load('organization'),
                'Manager profile',
                'MANAGER_PROFILE'
            );
            if (!$data) {
                $this->respondWithError('Error Occurred while fetching profile');
            }
            return $data;
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occurred while fetching profile');
        }
    }


    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('manager')->logout();
        return $this->respondWithSuccess(null, 'Successfully logged out', 'API_LOGOUT');
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth('manager')->refresh());
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
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
