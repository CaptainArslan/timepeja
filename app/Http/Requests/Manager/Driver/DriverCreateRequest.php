<?php

namespace App\Http\Requests\Manager\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class DriverCreateRequest extends FormRequest
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
            'name' => ['required', 'string',],
            'phone' => ['required', 'string', 'unique:drivers,phone',],
            'cnic' => ['required', 'string', 'unique:drivers,cnic',],
            'cnic_front' => ['required', 'string'],
            'cnic_back' => ['required', 'string'],
            'license_no' => ['required', 'string', 'unique:drivers,license_no',],
            'license_front' => ['required', 'string'],
            'license_back' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name required',
            'phone.required' => 'Phone required',
            'phone.unique' => 'Phone already exists',
            'cnic.required' => 'CNIC required',
            'cnic.unique' => 'CNIC already exists',
            'license_no.required' => 'License required',
            'license_no.unique' => 'License already exists',
            'cnic_front.required' => 'CNIC front required',
            'cnic_back.required' => 'CNIC back required',
            'license_front.required' => 'License front required',
            'license_back.required' => 'License back required',
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
