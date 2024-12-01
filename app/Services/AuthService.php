<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Services\OtpService;
use App\Traits\HandlesOtpVerification;
use Illuminate\Validation\ValidationException;

class AuthService
{
    use HandlesOtpVerification;

    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function createUserToken(User $user)
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function sendVerificationCode(User $user)
    {
        return $this->otpService->sendVerificationCode($user);
    }
}