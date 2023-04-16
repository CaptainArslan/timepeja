<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

class DriverStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'o_id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string', 'unique:drivers,phone'],
            'cnic' => ['required', 'string', 'regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/', 'unique:drivers,cnic'],
            'license_no' => [
                'required',
                'string',
                // 'regex:/^\d{10}-[A-Za-z]+$/',
                'unique:drivers,license_no'
            ],
            'cnic_front' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'cnic_back' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'license_front' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'license_back' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'status' => ['nullable', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'o_id.required' => 'Organization required',
            'name.required' => 'Name required',
            'phone.required' => 'Phone required',
            'phone.unique' => 'Phone already exists',
            'cnic.required' => 'CNIC required',
            'cnic.unique' => 'CNIC already exists',
            'cnic.regex' => 'CNIC format is invalid',
            'license_no.required' => 'License required',
            'license_no.unique' => 'License already exists',
            'license_no.regex' => 'License format is invalid',
            'cnic_front.required' => 'CNIC front required',
            'cnic_front.image' => 'CNIC front must be an image',
            'cnic_front.mimes' => 'CNIC front must be a file of type: jpeg, png, jpg, gif, svg',
            'cnic_front.max' => 'CNIC front may not be greater than 2048 kilobytes',
            'cnic_back.required' => 'CNIC back required',
            'cnic_back.image' => 'CNIC back must be an image',
            'cnic_back.mimes' => 'CNIC back must be a file of type: jpeg, png, jpg, gif, svg',
            'cnic_back.max' => 'CNIC back may not be greater than 2048 kilobytes',
            'license_front.required' => 'License front required',
            'license_front.image' => 'License front must be an image',
            'license_front.mimes' => 'License front must be a file of type: jpeg, png, jpg, gif, svg',
            'license_front.max' => 'License front may not be greater than 2048 kilobytes',
            'license_back.required' => 'License back required',
            'license_back.image' => 'License back must be an image',
            'license_back.mimes' => 'License back must be a file of type: jpeg, png, jpg, gif, svg',
            'license_back.max' => 'License back may not be greater than 2048 kilobytes',
        ];
    }
}
