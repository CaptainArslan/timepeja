<?php

namespace App\Http\Requests\Manager\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class LoginRequest extends FormRequest
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
            'phone' => ['required', 'numeric'],
            'password' => [
                'required',
                'string',
                'between:8,25',
            ],
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Phone number is required',
            'phone.numeric' => 'Phone number must be numeric',
            'phone.digits' => 'Phone number must be 11 digits',
            'password.required' => 'Password is required',
            'password.between' => 'Password must be between :min and :max characters',
            'password.regex' =>
            'The password must contain at least one uppercase letter, one lowercase letter,
            one number, and one special character.'
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
