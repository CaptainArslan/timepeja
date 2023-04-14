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
            'o_id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string', 'unique:drivers,phone'],
            'cnic' => ['required', 'string', 'unique:drivers,cnic'],
            'license' => ['required', 'string', 'unique:drivers,license_no'],
            'status' => ['required', 'numeric'],
            'cnic_front' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'cnic_back' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'license_front' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'license_back' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'o_id.required' => 'Organization required'
        ];
    }
}
