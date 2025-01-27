<?php

namespace App\Http\Requests\Manager\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ForgetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'otp' => ['required', 'string'],
            'phone' => ['required', 'exists:managers,phone'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'between:8,25',
            ],
        ];
    }

    public function messages()
    {
        return [
            'otp.required' => 'OTP is required',
            'otp.string' => 'OTP must be a string',
            'phone.required' => 'Phone is required',
            'password.required' => 'Password is required',
            'password.string' => 'Password must be a string',
            'password.confirmed' => 'Password confirmation does not match',
            'password.between' => 'Password must be between 8 and 25 characters',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->first();
        $response = response()->json([
            'success' => false,
            'message' => $errors,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
        throw new HttpResponseException($response);
    }
}
