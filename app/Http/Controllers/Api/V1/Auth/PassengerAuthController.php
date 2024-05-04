<?php

namespace App\Http\Controllers\Api\V1\Auth;

use ApiHelper;
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
            // $passenger->gaurd_code = substr(uniqid(), -8);
            // $passenger->otp = rand(1000, 9999);
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

        ApiHelper::saveDeviceToken($request, $passenger);


        return $this->respondWithSuccess($passenger, 'Login successfully', 'LOGIN_API_SUCCESS', [
            'content-type' => 'application/json',
            'Authorization' => $token
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
                $data = $passenger->only('id', 'name', 'phone', 'otp');
                return $this->respondWithSuccess($data, 'Otp Sent Successfully', 'API_GET_CODE');
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



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profileUpload(Request $request): jsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ],
            [
                'profile_picture.required' => 'Profile Picture is required',
                'profile_picture.image' => 'Profile Picture must be an image',
                'profile_picture.mimes' => 'Profile Picture must be a file of type: jpeg, png, jpg, gif',
                'profile_picture.max' => 'Profile Picture may not be greater than 2048 kilobytes',
            ]
        );

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $passenger = auth('passenger')->user();

            if ($request->hasFile('profile_picture') && $passenger->image != null) {
                removeImage($passenger->image, '/passenger/profiles/');
            }
            $image = uploadImage($request->file('profile_picture'), '/passenger/profiles/', 'profile');

            $passenger->image = $image;
            // $data = $passenger->select('id', 'picture')->first();
            if ($passenger->save()) {
                return $this->respondWithSuccess($passenger->only('id', 'image'), 'Profile Updated', 'PASSENGER_PROFILE_UPDATED');
            } else {
                return $this->respondWithError('Profile not Updated');
            }
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while profile Updated');
        }
    }


    public function profileUpdate(Request $request): jsonResponse
    {
        $passenger = auth('passenger')->user();
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255', 'unique:passengers,phone,' . $passenger->id],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:passengers,email,' . $passenger->id],
                'address' => ['required', 'string', 'max:255'],
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
        try {
            $passenger->name = $request->name;
            $passenger->email = $request->email;
            $passenger->phone = $request->phone;
            $passenger->address = $request->address;
            if ($passenger->save()) {
                return $this->respondWithSuccess($passenger, 'Profile Updated', 'PASSENGER_PROFILE_UPDATED');
            } else {
                return $this->respondWithError('Error Occured while profile Updated');
            }
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while profile Updated');
        }
    }
}
