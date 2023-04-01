<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\Manager;
use Illuminate\Support\Str;
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
            return $this->respondWithError("Please fill the form correctly");
        }

        $manager = Manager::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->first();

        if (!$manager) {
            return $this->respondWithError("Invalid phone number or verification code");
        }

        if (empty($manager->password)) {
            Manager::where('phone', $request->phone)->update([
                'password' => Hash::make($request->password),
                // ,
            ]);
            $manager->makeHidden(['password']);
            $manager->makeHidden(['token']);
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
                'between:8,25',
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

        $manager->where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->update([
                'password' => Hash::make($request->password),
            ]);
        $manager->makeHidden('password');

        return $this->respondWithSuccess($manager, 'Password Updated Successfully', 'PASSWORD_UPDATE');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        return $this->respondWithSuccess(
            auth('manager')->user(),
            // ->load('organization')
            'Manager profile',
            'MANAGER_PROFILE'
        );
        // return response()->json(
        //     auth('manager')->user() // ->load('organization')
        // );
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('manager')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('manager')->refresh());
    }
}

// this query is for to load load data from organization with its type
// $user = auth('api')->user()->load(['organization' => function ($query) {
//     $query->select('id', 'name', 'branch_name', 'branch_code', 'email', 'phone', 'address', 'o_type_id');
//     $query->with(['organizationType' => function ($query) {
//         $query->select('id', 'name', 'desc');
//     }]);
// }]);
