<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetNewPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone' => [
                'bail',
                'required',
                'string',
                'regex:/^\+\d{10,15}$/',
                'exists:users,phone'
            ],
            'code' => 'bail|required|string|size:6|digits:6',
            'password' => 'bail|required|string|min:6|confirmed',
        ];
    }
    
    public function messages()
    {
        return [
            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'The phone number format is invalid.',
            'phone.exists' => 'This phone number is not registered in our system.',
            'code.required' => 'The verification code is required.',
            'code.size' => 'The verification code must be exactly :size digits.',
            'code.digits' => 'The verification code must contain only numbers.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least :min characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }

    /**
     * Define body parameters for Scribe.
     *
     * @return array
     */
    public function bodyParameters()
    {
        return [
            'phone' => [
                'description' => 'The registered phone number of the user.',
                'example' => '+1234567890',
            ],
            'code' => [
                'description' => 'The 6-digit verification code sent to the user.',
                'example' => '123456',
            ],
            'password' => [
                'description' => 'The new password for the user account.',
                'example' => 'newpassword123',
            ],
            'password_confirmation' => [
                'description' => 'The confirmation of the new password.',
                'example' => 'newpassword123',
            ],
        ];
    }
}