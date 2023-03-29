<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
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
        $this->middleware('auth:manager', ['except' => ['login', 'register', 'getVerificationCode', 'forgetPassword']]);
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

        $manager = Manager::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->first();

        if (!$manager) {
            return $this->respondWithError("Invalid phone number or verification code");
        }

        if (empty($manager->token) && empty($manager->password)) {
            Manager::where('phone', $request->phone)->update([
                'password' => Hash::make($request->password),
                'token' => Str::random(60),
            ]);
            $manager->makeHidden(['password']);
            return $this->respondWithSuccess($manager, 'Manager registered successfully', 'REGISTER_API_SUCCESS');
        } else {
            return $this->respondWithError('Manager alreasy exist. Please login ');
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

        if (!$token = auth('manager')->attempt($credentials)) {
            return $this->respondWithError('Invalid phone number or password');
        }

        $user = auth('manager')->user();
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

        $manager = Manager::where('phone', $fields['phone'])->first();
        if (!empty($manager)) {
            $manager = Manager::find($manager->id);
            $manager->otp = substr(uniqid(), -4);
            $save = $manager->save();
            if ($save) {
                return $this->respondWithSuccess($manager->otp, 'Otp Sent Successfully', 'API_GET_CODE');
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

        $manager = Manager::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->first();

        if (!$manager) {
            return $this->respondWithError('invalid phone or verification code');
        }

        Manager::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->update([
                'password' => Hash::make($request->password),
            ]);
        $manager->makeHidden('password');

        return $this->respondWithSuccess($manager, 'Password Updated Successfully', 'PASSWORD_UPDATE');
    }
}
