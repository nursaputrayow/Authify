<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'The phone number format is invalid.',
            'phone.exists' => 'This phone number is not registered in our system.',
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
                'description' => 'The registered phone number of the user to reset the password for.',
                'example' => '+1234567890',
            ],
        ];
    }
}