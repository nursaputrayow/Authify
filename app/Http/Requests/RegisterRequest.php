<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'bail|required|string|min:2|max:50',
            'phone' => [
                'bail',
                'required',
                'string',
                'regex:/^\+\d{10,15}$/',
                'unique:users,phone',
            ],
            'email' => 'bail|required|email|unique:users,email',
            'password' => 'bail|required|string|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.min' => 'The name must be at least :min characters.',
            'name.max' => 'The name may not be greater than :max characters.',
            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'The phone number format is invalid.',
            'phone.unique' => 'This phone number has already been taken.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'This email has already been taken.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least :min characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}