<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterNewUserFormRequest extends FormRequest
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
            'username'          => 'required|string|min:3|unique:users,username',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:6',
            'confirm_password'  => 'required|string|same:password',
        ];
    }
}
