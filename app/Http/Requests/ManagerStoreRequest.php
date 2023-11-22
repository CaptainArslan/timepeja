<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagerStoreRequest extends FormRequest
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
            'org_name' => 'required|string|max:255',
            'org_type ' => 'nullable|numeric',
            'org_email' => 'required|email|unique:organizations,email',
            'org_phone' => 'required|regex:/^03\d{2}-\d{7}$/',
            'org_state' => 'required|numeric',
            'org_city' => 'required|numeric',

            'org_head_name' => 'required|string|max:255',
            'org_head_phone' => 'required|regex:/^03\d{2}-\d{7}$/',
            'org_head_email' => 'required|email|unique:organizations,head_email',

            'man_name' => 'required|string|max:255',
            'man_email' => 'nullable|email|unique:managers,email',
            'man_phone' => 'required|unique:managers,phone',
            'man_pic' => 'nullable|mimes:jpg,png',

            'wallet' => 'required',
            'payment' => 'required',

            'org_amount' => 'nullable|numeric',
            'org_trail_start_date' => 'nullable|date',
            'org_trail_end_date' => 'nullable|date',

            'driver_amount' => 'nullable|numeric',
            'driver_trial_start_date' => 'nullable|date',
            'driver_trial_end_date' => 'nullable|date',

            'passenger_amount' => 'nullable|numeric',
            'passenger_trail_start_date' => 'nullable|date',
            'passenger_trail_end_date' => 'nullable|date',
        ];
    }

    public function messages()
    {
        return [
            'org_name.required' => 'Organization name required!',
            'org_type.required' => 'Organization Organization type required!',
            'org_email.required' => 'Organization email required',
            'org_phone.required' => 'Organization phone required!',
            'org_state.required' => 'Organization state required!',
            'org_city.required' => 'Organization city required!',

            'org_head_name.required' => 'Organization head name required!',
            'org_head_phone.required' => 'Organization head phone required!',
            'org_head_email.required' => 'Organization head email required!',

            'man_name.required' => 'Manager name required!',
            'man_email.required' => 'Manager email required!',
            'man_email.unique' => 'Manager email already taken!',
            'man_phone.required' => 'Manager phone required!',
        ];
    }
}
