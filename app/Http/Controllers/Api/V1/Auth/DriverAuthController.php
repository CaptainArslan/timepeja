<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\Driver;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;

class DriverAuthController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'getVerificationCode', 'forgetPassword']]);
    }

    /**
     * Driver registration
     *
     * @param   Request       driver registration request
     *
     * @return  JsonResponse            return object of driver after registration
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'phone' => ['required', 'numeric', 'digits:11'],
            'otp' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'between:8,255',
                // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'password_confirmation' => ['required', 'string', 'between:8,255'],
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
            return $this->respondWithError("Please fill the form correctly");
        }

        $driver = Driver::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->first();

        if (!$driver) {
            return $this->respondWithError("Invalid phone number or verification code");
        } elseif ($driver->status == 0) {
            return $this->respondWithError("This driver is blocked. Please contact your manager");
        }

        if (empty($driver->password)) {
            $driver->where('phone', $request->phone)->update([
                'password' => Hash::make($request->password),
                'token' => Str::random(60),
            ]);
            $driver->makeHidden(['password']);
            return $this->respondWithSuccess($driver, 'Driver registered successfully', 'REGISTER_API_SUCCESS');
        } else {
            return $this->respondWithError('Driver alreasy exist. Please login ');
        }
    }

    /**
     * Driver login api function
     *
     * @param   Request       $request  Driver login request
     *
     * @return  JsonResponse            Driver object after successfull login
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'numeric', 'digits:11'],
            'password' => [
                'required',
                'string',
                'between:8,255',
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
            return response()->json([
                'success' => false,
                'message' => "Please fill the form correctly"
            ], 401);
        }

        $credentials = $request->only(['phone', 'password']);

        if (!$token = auth('driver')->attempt($credentials)) {
            return $this->respondWithError('Invalid phone number or password');
        }

        $user = auth('driver')->user();
        if (!$user) {
            return $this->respondWithError('User not Found');
        }
        $user->makeHidden('password');

        return $this->respondWithSuccess($user, 'Login successfully', 'LOGIN_API_SUCCESS', [
            'content-type' => 'application/json',
            'uid' => $user->email,
            // 'access-token' => $user->token,
            'Authorization' => 'Bearer ' . $token
        ]);
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
        $validate = Validator::make($fields, [
            'phone' => ['required', 'numeric', 'digits:11'],
        ], [
            'phone.required' => 'Phone number is required',
            'phone.numeric' => 'Phone number must be numeric',
            'phone.digits' => 'Phone number must be 11 digits',
        ]);

        if ($validate->fails()) {
            return $this->respondWithError('Please fill the form correctly');
        }

        $driver = Driver::where('phone', $fields['phone'])->first();
        if (!empty($driver)) {
            $driver = Driver::find($driver->id);
            $driver->otp = rand(1000, 9999);
            $save = $driver->save();
            if ($save) {
                return $this->respondWithSuccess($driver->otp, 'Otp Sent Successfully', 'API_GET_CODE');
            } else {
                return $this->respondWithError('Error Occured while sending otp');
            }
        } else {
            return $this->respondWithError('Invalid Phone number provided');
        }
    }

    /**
     * [forgetPassword description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => ['required', 'string'],
            'phone' => ['required', 'numeric', 'digits:11'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'between:8,255',
                // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
        ], [
            'phone.required' => 'Phone is required',
            'password.required' => 'Password is required',
            'otp.required' => 'Verification code is required',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError('Please fill the forn correctly');
        }

        $driver = Driver::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->first();

        if (!$driver) {
            return $this->respondWithError('invalid phone or verification code');
        }

        $driver->where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->update([
                'password' => Hash::make($request->password),
            ]);
        $driver->makeHidden('password');

        return $this->respondWithSuccess($driver, 'Password Updated Successfully', 'PASSWORD_UPDATE');
    }
}