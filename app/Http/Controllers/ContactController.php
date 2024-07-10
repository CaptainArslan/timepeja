<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function send(Request $request): JsonResponse
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        // Send error response if validation fails
        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            Mail::to(env('MAIL_TO'))->send(new ContactUsMail($request->all()));
            return $this->respondWithSuccess(null, 'Thank you for contacting us. We will get back to you shortly.', 'EMAIL_SENT');
        } catch (\Throwable $th) {
            return $this->respondWithError('Something went wrong. Please try again later.');
        }
    }
}
