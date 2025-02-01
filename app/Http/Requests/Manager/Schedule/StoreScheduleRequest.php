<?php

namespace App\Http\Requests\Manager\Schedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class StoreScheduleRequest extends FormRequest
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
            'route_id' => ['required', 'numeric', 'exists:routes,id'],
            'vehicle_id' => ['required', 'numeric', 'exists:vehicles,id'],
            'driver_id' => ['required', 'numeric', 'exists:drivers,id'],
            'date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
        ];
    }

    public function messages()
    {
        return [
            'route_id.required' => 'Route is required',
            'route_id.numeric' => 'Route must be a number',
            'route_id.exists' => 'Route not found',
            'vehicle_id.required' => 'Vehicle is required',
            'vehicle_id.numeric' => 'Vehicle must be a number',
            'vehicle_id.exists' => 'Vehicle not found',
            'driver_id.required' => 'Driver is required',
            'driver_id.numeric' => 'Driver must be a number',
            'driver_id.exists' => 'Driver not found',
            'date.required' => 'Date is required',
            'date.date' => 'Date must be a date',
            'date.date_format' => 'Date must be in Y-m-d format',
            'date.after_or_equal' => 'Date must be today or later',
            'time.required' => 'Time is required',
            'time.date_format' => 'Time must be in H:i format',
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
