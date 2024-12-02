<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends FormRequest
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
                'description' => 'The phone number of the user to verify.',
                'example' => '+1234567890',
            ],
            'code' => [
                'description' => 'The 6-digit verification code sent to the user.',
                'example' => '123456',
            ],
        ];
    }
}