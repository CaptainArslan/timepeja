<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class MediaUploadRequest extends FormRequest
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
            'media.*' => ['required', 'file', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'media.*.required' => 'The media field is required.',
            'media.*.file' => 'The media must be a valid file.',
            'media.*.mimes' => 'The media must be a file of type: jpeg, png, jpg, gif, svg, mp4, webm, ogg.',
            'media.*.max' => 'The media may not be greater than 2048 kilobytes.',
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
