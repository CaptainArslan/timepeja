<?php

namespace App\Http\Controllers\Api\V1\Auth;

use ApiHelper;
use App\Models\Otp;
use App\Models\Passenger;
use App\Services\SMSService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PassengerAuthController extends Controller
{
    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->middleware('auth:passenger', [
            'except' => [
                'login',
                'register',
                'getVerificationCode',
                'sendCode',
                'forgetPassword'
            ]
        ]);

        $this->smsService = $smsService;
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string',  'min:3', 'max:255'],
            'phone' => ['required', 'unique:passengers,phone'],
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
            // $passenger->gaurd_code = substr(uniqid(), -8);
            // $passenger->otp = rand(1000, 9999);
            $passenger->password = Hash::make($request->password);
            $passenger->save();
            return $this->respondWithSuccess($passenger, 'Passenger register successfully', 'PASSENGER_CREATED_SUCCESSFULLY');
        } catch (\Throwable $th) {
            return $this->respondWithError($th->getMessage());
        }
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required'],
            'password' => [
                'required',
                'string',
                'between:8,255',
            ],
        ], [
            'phone.required' => 'Phone number is required',
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

        ApiHelper::saveDeviceToken($request, $passenger);


        return $this->respondWithSuccess($passenger, 'Login successfully', 'LOGIN_API_SUCCESS', [
            'content-type' => 'application/json',
            'Authorization' => $token
        ]);
    }

    public function getVerificationCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required',],
        ], [
            'phone.required' => 'Phone number is required',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        $passenger = Passenger::where('phone', $request->phone)->first();

        if (!$passenger) {
            return $this->respondWithError('Invalid Phone number provided');
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

        try {
            $this->smsService->sendSMS($request->phone, "Your verification code is: $otp");
        } catch (\Throwable $th) {
            Log::error('Error sending SMS: ' . $th->getMessage());
            return $this->respondWithError('Error Occured while sending otp');
        }
        return $this->respondWithSuccess($oneTimePassword, 'Otp Sent Successfully', 'API_GET_CODE');
    }

    public function sendCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required',],
        ], [
            'phone.required' => 'Phone number is required',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
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

        try {
            $this->smsService->sendSMS($request->phone, "Your verification code is: $otp");
        } catch (\Throwable $th) {
            Log::error('Error sending SMS: ' . $th->getMessage());
            return $this->respondWithError('Error Occured while sending otp');
        }
        return $this->respondWithSuccess($oneTimePassword, 'Otp Sent Successfully', 'API_SEND_CODE');
    }

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

    public function profile(): JsonResponse
    {
        return $this->respondWithSuccess(
            auth('passenger')->user(),
            // ->load('organization')
            'Passenger profile',
            'PASSENGER_PROFILE'
        );
    }

    public function logout(): JsonResponse
    {
        auth('passenger')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth('passenger')->refresh());
    }

    public function profileUpload(Request $request): jsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'profile_picture' => ['required', 'string'],
            ],
            [
                'profile_picture.required' => 'Profile picture is required',
                'profile_picture.string' => 'Profile picture must be in string',
            ]
        );

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        $passenger = auth('passenger')->user();

        if (!$passenger) {
            return $this->respondWithError('Passenger not found');
        }

        if ($request->profile_picture) {
            Storage::delete($passenger->image);
        }

        $passenger->update([
            'image' => $request->profile_picture ? $request->profile_picture : $passenger->picture_name,
        ]);

        return $this->respondWithSuccess($passenger->only('id', 'image'), 'Profile Updated', 'PASSENGER_PROFILE_IMAGE_UPDATED');
    }

    public function profileUpdate(Request $request): jsonResponse
    {
        $passenger = auth('passenger')->user();
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255', 'unique:passengers,phone,' . $passenger->id],
                'email' => ['nullable', 'string', 'email', 'max:255', 'unique:passengers,email,' . $passenger->id],
                'address' => ['nullable', 'string', 'max:255'],
                'image' => ['nullable', 'string', 'max:255'],
            ],
            [
                'name.required' => 'Full name is required',
                'name.string' => 'Name must be in string',

                'phone.required' => 'Phone is required',
                'phone.string' => 'phone must be in string',

                'email.required' => 'Email is required',
                'email.string' => 'email must be in string',
                'email.email' => 'email must be in email format',

                'address.required' => 'Address is required',
                'address.string' => 'address must be in string',
            ]
        );

        if ($validator->fails()) {
            return $this->respondWithError(implode(",", $validator->errors()->all()));
        }

        // dd($passenger->image , $request->image);
        // try {
        $passenger->name = $request->name;
        $passenger->email = $request->email;
        $passenger->phone = $request->phone;
        $passenger->address = $request->address;
        $passenger->image = $request->image ?? $passenger->image;
        if ($passenger->save()) {
            return $this->respondWithSuccess($passenger, 'Profile Updated', 'PASSENGER_PROFILE_UPDATED');
        } else {
            return $this->respondWithError('Error Occured while profile Updated');
        }
        // } catch (\Throwable $th) {
        //     return $this->respondWithError('Error Occured while profile Updated');
        // }
    }
}
