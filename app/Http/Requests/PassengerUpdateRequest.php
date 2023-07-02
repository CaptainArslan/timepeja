<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PassengerUpdateRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation()
    {
        $this->merge([
            'phone' => str_replace('-', '', $this->phone),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'phone' => ['required', 'string', 'unique:passengers,phone'],
            'email' => ['nullable', 'email', 'unique:passengers,email'],
            'image' => [
                'nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'
            ],
            'status' => ['nullable', 'numeric'],
        ];
    }

    /**
     * Custom Messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name' => ['required' => 'The name field is required.', 'string' => 'The name must be a string.'],

            'phone' => [
                'required' => 'The phone field is required.',
                'string' => 'The phone must be a string.',
                'unique' => 'The phone number has already been taken.'
            ],

            'email' => [
                'required' => 'The email field is required.',
                'email' => 'Please enter a valid email address.',
                'unique' => 'The email address has already been taken.'
            ],

            'image' => [
                'image' => 'The file must be an image.',
                'mimes' => 'Only JPEG, PNG, JPG, GIF, and SVG images are allowed.',
                'max' => 'The image size should not exceed 2MB.'
            ],

            'status' => ['nullable' => 'The status field must be null or numeric.']
        ];
    }
}
