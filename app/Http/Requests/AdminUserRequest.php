<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email_name' => 'required',
            'password' => 'nullable|confirmed|min:8',
        ];
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required' => trans('users/admin_lang.fields.first_name_required'),
            'last_name.required' => trans('users/admin_lang.fields.last_name_required'),
            'email.required' => trans('users/admin_lang.fields.email_required'),
            'password.confirmed' => trans('users/admin_lang.fields.password_confirmed'),
            'password.min' => trans('users/admin_lang.fields.password_min'),

        ];
    }
}
