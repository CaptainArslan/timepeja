<?php

namespace App\Http\Requests\Manager\Route;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class CreateRouteRequest extends FormRequest
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
            'number' => ['required', 'integer'],
            'from' => ['required', 'array'],
            'to' => ['required', 'array'],
            'way_points' => ['nullable', 'array'],
        ];
    }

    public function messages()
    {
        return [
            'number.required' => 'Route number is required',
            'number.integer' => 'Route number must be an integer',
            'from.required' => 'From location is required',
            'from.array' => 'From location must be an array',
            'to.required' => 'To location is required',
            'to.array' => 'To location must be an array',
            'way_points.array' => 'Way points must be an array',
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
