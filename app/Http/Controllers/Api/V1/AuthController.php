<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create($request->only('email', 'name', 'password')); //Retrieving only the email and password data
        // $user->assignRole('Client');

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
        ]);
    }

    public function login(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::where('email', $request->email)->where('register_type', 'regular_login')->first(); //Retrieving only the email and password data


        if (!isset($user)) {

            return response()->json([
                'status' => 'failed',
                'message' => 'User dont exist',
            ]);
        }

        if ($user->is_deleted) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User dont exist',
            ]);
        }

        if (!isset($user->password) || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Wrong password',
            ]);
        }

        //Adding FCM Token
        $fcm_token = isset($request->fcm_token) ? $request->fcm_token : '';
        User::where('id', '=', $user->id)->update(['fcm_token' => $fcm_token]);

        $user->memberships = $user->memberships;

        //deleting refresh tokens of user before generating new
        $user->deleteRefreshTokenByEmail($request->email);
        //Deleting tokens
        $user->tokens()->delete();

        $token_obj = $user->createToken($request->email);
        $token = $token_obj->plainTextToken;
        $refresh_token = $token_obj->refreshToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token,
            'refresh_token' => $refresh_token
        ]);
    }


    public function profile($user_id)
    {

        $user = User::find($user_id);

        if ($user->exists()) {
            $user->memberships = $user->memberships;

            return response()->json([
                'status' => 'success',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'User dont exist'
            ]);
        }
    }


    public function logout(Request $request)
    {

        $this->validate($request, [
            'user_id' => 'required'
        ]);

        $user = User::where('id', $request->user_id)->first(); //Retrieving only the email and password data

        if (!isset($user)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User dont exist',
            ]);
        }

        //deleting refresh tokens of user before generating new
        $user->deleteRefreshTokenByEmail($user['email']);
        //Deleting tokens
        $user->tokens()->delete();


        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }

    public function profile_update(Request $request, $user_id)
    {

        if (isset($user_id)) {
            $user = User::find($user_id);

            $user->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to update profile'
            ]);
        }
    }
    //API
    public function forgot_password(Request $request)
    {

        $this->validate($request, [
            'email' => 'required'
        ]);


        $supporting = new Supporting();
        $token = $supporting->token_string(80, 'forgot_password', 'token_string');

        $email = $request->input('email');
        $user = User::where('email', $email)->where('register_type', 'regular_login')->get()->toArray();

        if (!empty($user)) {
            ForgotPassword::where('email', $email)->delete();
            ForgotPassword::insert([
                'email' => $email,
                'token_string' => $token,
            ]);


            $subject = 'Forgot Password';
            $message = 'Click the link to reset the password: <a href="https://shred.pk/reset-password/' . $token . '">Reset Password</a>';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $success = mail($email, $subject, $message, $headers);

            return response()->json([
                'status' => 'success',
                'message' => 'Reset link has been send to the mail.',
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'User dont exist',
            ]);
        }
    }

    //API
    public function forgot_password(Request $request)
    {

        $this->validate($request, [
            'email' => 'required'
        ]);


        $supporting = new Supporting();
        $token = $supporting->token_string(80, 'forgot_password', 'token_string');

        $email = $request->input('email');
        $user = User::where('email', $email)->where('register_type', 'regular_login')->get()->toArray();

        if (!empty($user)) {
            ForgotPassword::where('email', $email)->delete();
            ForgotPassword::insert([
                'email' => $email,
                'token_string' => $token,
            ]);


            $subject = 'Forgot Password';
            $message = 'Click the link to reset the password: <a href="https://shred.pk/reset-password/' . $token . '">Reset Password</a>';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $success = mail($email, $subject, $message, $headers);

            return response()->json([
                'status' => 'success',
                'message' => 'Reset link has been send to the mail.',
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'User dont exist',
            ]);
        }
    }
}
