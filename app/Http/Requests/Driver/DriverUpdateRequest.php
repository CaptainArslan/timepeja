<?php

namespace App\Http\Requests\Driver;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DriverUpdateRequest extends FormRequest
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
            'id' => [
                'required',
                'numeric',
                'exists:drivers,id'
            ],
            'o_id' => ['required', 'numeric', 'exists:organizations,id'],
            'name' => ['required', 'string'],
            'phone' => [
                'required',
                'string',
                Rule::unique('drivers', 'phone')->ignore($this->id),
            ],
            'cnic' => [
                'required',
                'string',
                Rule::unique('drivers', 'cnic')->ignore($this->id),
            ],
            'license' => [
                'required',
                'string',
                Rule::unique('drivers', 'license_no')->ignore($this->id),
            ],
            'cnic_front' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'cnic_back' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'license_front' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'license_back' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'status' => ['numeric'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'The driver ID is required.',
            'id.numeric' => 'The driver ID should be a number.',
            'id.exists' => 'The driver with the given ID does not exist.',
            'o_id.required' => 'The organization ID is required.',
            'o_id.numeric' => 'The organization ID should be a number.',
            'o_id.exists' => 'The organization with the given ID does not exist.',
            'name.required' => 'The driver name is required.',
            'name.string' => 'The driver name should be a string.',
            'phone.required' => 'The phone number is required.',
            'phone.string' => 'The phone number should be a string.',
            'phone.unique' => 'The phone number has already been taken.',
            'cnic_number.required' => 'The CNIC number is required.',
            'cnic_number.string' => 'The CNIC number should be a string.',
            'cnic_number.unique' => 'The CNIC number has already been taken.',
            'license_number.required' => 'The license number is required.',
            'license_number.string' => 'The license number should be a string.',
            'license_number.unique' => 'The license number has already been taken.',
        ];
    }
}
