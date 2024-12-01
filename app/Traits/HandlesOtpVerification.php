<?php

namespace App\Traits;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

trait HandlesOtpVerification
{
    public function validateOtp(User $user, $inputCode)
    {
        $verificationCode = $user->verificationCodes()
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$verificationCode) {
            $this->logOtpError($user->id, 'OTP expired or not found');
            throw $this->otpValidationException('Kode verifikasi tidak valid atau telah kadaluarsa.');
        }

        if (!Hash::check($inputCode, $verificationCode->code)) {
            $this->logOtpError($user->id, 'OTP mismatch');
            throw $this->otpValidationException('Kode verifikasi tidak valid.');
        }

        $verificationCode->delete();

        return true;
    }

    protected function logOtpError(int $userId, string $message): void
    {
        Log::warning("OTP error for user {$userId}: {$message}");
    }

    protected function otpValidationException(string $message): ValidationException
    {
        return ValidationException::withMessages(['otp' => $message]);
    }
}