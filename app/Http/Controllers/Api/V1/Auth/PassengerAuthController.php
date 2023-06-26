<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PassengerAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:passenger', ['except' => ['login', 'register', 'getVerificationCode', 'forgetPassword']]);
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
            'name' => ['required', 'string',  'min:3', 'max:255'],
            'phone' => ['required', 'numeric', 'digits:11', 'unique:passengers,phone'],
            'password' => ['required', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'The name field is required.',
            'phone.required' => 'The phone field is required.',
            'phone.unique' => 'The phone number is already registered.',
            'verification_code.required' => 'The verification code field is required.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $passenger = new Passenger();
            $passenger->name = $request->name;
            $passenger->phone = $request->phone;
            $passenger->unique_id = substr(uniqid(), -8);
            $passenger->gaurd_code = substr(uniqid(), -8);
            $passenger->password = Hash::make($request->password);
            $passenger->save();
            return $this->respondWithSuccess($passenger, 'Passenger register successfully', 'PASSENGER_CREATED_SUCCESSFULLY');
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
            'phone' => ['required', 'numeric', 'digits:11'],
            'password' => [
                'required',
                'string',
                'between:8,255',
            ],
        ], [
            'phone.required' => 'Phone number is required',
            'phone.numeric' => 'Phone number must be numeric',
            'phone.digits' => 'Phone number must be 11 digits',
            'password.required' => 'Password is required',
            'password.between' => 'Password must be between :min and :max characters',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        $credentials = $request->only(['phone', 'password']);


        if (!$token = auth('passenger')->attempt($credentials)) {
            return $this->respondWithError('Invalid phone number or password');
        }

        $passenger = auth('passenger')->user();
        if (!$passenger) {
            return $this->respondWithError('User not Found');
        }

        return $this->respondWithSuccess($passenger, 'Login successfully', 'LOGIN_API_SUCCESS', [
            'content-type' => 'application/json',
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
        $validator = Validator::make($fields, [
            'phone' => ['required', 'numeric', 'digits:11'],
        ], [
            'phone.required' => 'Phone number is required',
            'phone.numeric' => 'Phone number must be numeric',
            'phone.digits' => 'Phone number must be 11 digits',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        $passenger = Passenger::where('phone', $fields['phone'])->first();
        if (!empty($passenger)) {
            $passenger = Passenger::find($passenger->id);
            $passenger->otp = rand(1000, 9999);
            $save = $passenger->save();
            if ($save) {
                return $this->respondWithSuccess($passenger->otp, 'Otp Sent Successfully', 'API_GET_CODE');
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
    public function forgetPassword(Request $request): JsonResponse
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
            return $this->respondWithError($validator->errors()->first());
        }

        $passenger = Passenger::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->first();

        if (!$passenger) {
            return $this->respondWithError('invalid phone or verification code');
        }

        Passenger::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->update([
                'password' => Hash::make($request->password),
            ]);
        $passenger->makeHidden('password');

        return $this->respondWithSuccess(null, 'Password Updated Successfully', 'PASSWORD_UPDATE');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(): JsonResponse
    {
        return $this->respondWithSuccess(
            auth('passenger')->user(),
            // ->load('organization')
            'Passenger profile',
            'PASSENGER_PROFILE'
        );
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('passenger')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth('passenger')->refresh());
    }
}
