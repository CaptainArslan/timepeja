<?php

namespace App\Http\Requests\Manager\Schedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class PublishScheduleRequest extends FormRequest
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
            'schedule_ids' => ['required'],
            'schedule_ids.*' => ['integer', 'exists:schedules,id'],
            'date' => ['nullable', 'date', 'date_format:Y-m-d'],
        ];
    }

    public function messages()
    {
        return [
            'schedule_ids.required' => 'Schedule ids are required',
            'schedule_ids.*.integer' => 'ID must be an integer',
            'schedule_ids.*.exists' => 'Invalid ID provided',
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
