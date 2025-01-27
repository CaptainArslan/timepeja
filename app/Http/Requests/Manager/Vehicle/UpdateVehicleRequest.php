<?php

namespace App\Http\Requests\Manager\Vehicle;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
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
            'vehicle_type_id' => ['required', 'numeric', 'exists:vehicle_types,id'],
            'number' => ['required', 'string', Rule::unique('vehicles')->ignore($this->id)],
            'front_pic' => ['required', 'string'],
            'number_pic' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'vehicle_type_id.required' => 'Vehicle type is required',
            'vehicle_type_id.numeric' => 'Vehicle type must be a number',
            'vehicle_type_id.exists' => 'Vehicle type does not exist',
            'number.required' => 'Number is required',
            'number.string' => 'Number must be a string',   
            'number.unique' => 'Number already exists',
            'front_pic.required' => 'Front picture is required',
            'front_pic.string' => 'Front picture must be a string',
            'number_pic.required' => 'Number picture is required',
            'number_pic.string' => 'Number picture must be a string',
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
