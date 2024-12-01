<?php

namespace App\Services;

use App\Models\VerificationCode;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Mail\VerificationCodeMail;

class OtpService
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function sendVerificationCode($user)
    {
        $key = "otp_attempts_{$user->id}";
        $attempts = Cache::get($key, 0);

        if ($attempts >= 3) {
            throw ValidationException::withMessages(['otp' => 'Too many attempts. Please try again later.']);
        }

        try {
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            VerificationCode::create([
                'user_id' => $user->id,
                'code' => Hash::make($code),
                'expires_at' => Carbon::now()->addMinutes(60),
            ]);

            $message = "Your verification code is: {$code}. This code will expire in 60 minutes.";
            $this->twilioService->sendWhatsAppMessage($user->phone, $message);
            Mail::to($user->email)->send(new VerificationCodeMail($code));

            Cache::put($key, $attempts + 1, now()->addMinutes(60));

            return $code;
        } catch (\Exception $e) {
            Log::error('Failed to send verification code', [
                'user_id' => $user->id,
                'error_message' => $e->getMessage(),
            ]);
            throw ValidationException::withMessages(['otp' => 'Failed to send verification code. Please try again later.']);
        }
    }
}