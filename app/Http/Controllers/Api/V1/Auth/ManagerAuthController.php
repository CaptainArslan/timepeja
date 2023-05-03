<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;

class ManagerAuthController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
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
                    'webLogin'
                ]
            ]
        );
    }

    /**
     * Manager registration
     *
     * @param   Request       Manager registration request
     *
     * @return  JsonResponse            return object of Manager after registration
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
                // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
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

            if (empty($manager->password)) {
                $manager::where('phone', $request->phone)->update([
                    'password' => Hash::make($request->password),
                    'status' => Manager::STATUS_ACTIVE,
                ]);
                return $this->respondWithSuccess($manager, 'Manager registered successfully', 'REGISTER_API_SUCCESS');
            } else {
                return $this->respondWithError('Manager alreasy exist. Please login.');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * [login description]
     *
     * @param   Request       $request  [$request description]
     *
     * @return  JsonResponse            [return description]
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

            if (!$token = auth('manager')->attempt($credentials)) {
                return $this->respondWithError('Invalid phone number or password');
            }

            $user = auth('manager')->user();
            if (!$user) {
                return $this->respondWithError('User not Found');
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
     * [getVerificationCode description]
     *
     * @param   Request       $request  [$request description]
     *
     * @return  JsonResponse            [return description]
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
                $manager->otp = rand(1000, 9999);
                $save = $manager->save();
                if ($save) {
                    $data = $manager->only('id', 'name', 'phone', 'otp');
                    return $this->respondWithSuccess($data, 'Otp Sent Successfully', 'API_GET_CODE');
                } else {
                    return $this->respondWithError('Error Occured while sending otp');
                }
            } else {
                return $this->respondWithError('Invalid Phone number provided');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * [forgetPassword description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
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
                // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
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
            throw $th;
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            $data = $this->respondWithSuccess(
                auth('manager')->user(),
                'Manager profile',
                'MANAGER_PROFILE'
            );
            if (!$data) {
                $this->respondWithError('Error Occured while fetching profile');
            }
            return $data;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('manager')->logout();
        return $this->respondWithSuccess(null, 'Successfully logged out', 'API_LOGOUT');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth('manager')->refresh());
    }

    /**
     * [login description]
     *
     * @param   Request       $request  [$request description]
     *
     * @return  JsonResponse            [return description]
     */
    public function webLogin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'numeric'],
            'password' => [
                'required',
                'string',
                'between:8,25',
                // 'regex:/^(?=.*[a-z])(?=.*[A- Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
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
            throw $th;
        }
    }
}
