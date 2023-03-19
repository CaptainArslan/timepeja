<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Manager;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
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
     * [register description]
     *
     * @param   Request       $request  [$request description]
     *
     * @return  JsonResponse            [return description]
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
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
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
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'otp.required' => 'Verification code is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        $manager = Manager::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->first();

        if (!$manager) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number or verification code'
            ], 401);
        }

        if (empty($manager->token) && empty($manager->password)) {
            Manager::where('phone', $request->phone)->update([
                'password' => Hash::make($request->password),
                'token' => Str::random(60),
            ]);
            $manager->makeHidden(['password']);

            return response()->json([
                'success' => true,
                'message' => 'Manager registered successfully',
                'data' => $manager,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Manager already exists. Please login.',
            ], 401);
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
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], 401);
        }

        $credentials = $request->only(['phone', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid phone number or password'
            ], 401);
        }

        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'User not found'
            ], 401);
        }
        $user->makeHidden('password');

        return response()->json(
            [
                'success' => true,
                'code' => 'LOGIN_API_SUCCESS',
                'message' => 'Login successful',
                'data' => $user,
            ],
            200,
            [
                'content-type' => 'application/json',
                'uid' => $user->email,
                'access-token' => $user->token,
                'Authorization' => 'Bearer ' . $token
            ]
        );
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
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validate->errors(),
            ], 401);
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
                    'code' => 'Get_OTP_API',
                    'data' => $manager->otp,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'errors' => ['Error Occured while sending otp']
                ], 401);
            }
        } else {
            return response()->json([
                'success' => false,
                'errors' => ['Invalid Phone number provided']
            ], 200);
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
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
        ], [
            'phone.required' => 'Phone is required',
            'password.required' => 'Password is required',
            'otp.required' => 'Verification code is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()->all(),
            ], 401);
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

        // if (!empty($manager->password)) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Password already set for this account',
        //     ], 401);
        // }

        Manager::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->update([
                'password' => Hash::make($request->password),
            ]);
            $manager->makeHidden('password');

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully',
            'code' => 'PASSWORD_UPDATE',
            'data' => $manager,
        ], 200);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }
}

// this query is for to load load data from organization with its type
// $user = auth('api')->user()->load(['organization' => function ($query) {
//     $query->select('id', 'name', 'branch_name', 'branch_code', 'email', 'phone', 'address', 'o_type_id');
//     $query->with(['organizationType' => function ($query) {
//         $query->select('id', 'name', 'desc');
//     }]);
// }]);