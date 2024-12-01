<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SetNewPasswordRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            Log::info('Register request received', ['phone' => $request->input('phone')]); 
            Log::info('Register request received', $request->all());
            $user = $this->authService->createUser($request->validated());
            $verificationCode = $this->authService->sendVerificationCode($user);

            return $this->successResponse([
                'user' => $user,
                'verification_code' => $verificationCode,
                'message' => 'A verification code has been sent to your phone.'
            ], 'User registered successfully. Please verify your phone number.', 201);
        } catch (ValidationException $e) {
            Log::error('Validation failed during registration', [
                'errors' => $e->errors()
            ]);
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->errorResponse('Registration failed', 500, ['error' => $e->getMessage()]);
        }
    }

    public function verify(VerifyRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        if (!$this->authService->verifyCode($user, $request->code)) {
            return $this->errorResponse('Invalid or expired verification code', 422);
        }

        $user->update([
            'is_verified' => true, 
            'phone_verified_at' => now(),
            'email_verified_at' => now(),
        ]);
        $token = $this->authService->createUserToken($user);

        return $this->successResponse([
            'user' => $user,
            'token' => $token
        ], 'Phone number verified successfully');
    }

    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->only('phone', 'password'))) {
                return $this->errorResponse('Invalid login credentials', 401);
            }

            $user = Auth::user();
            if (!$user->is_verified) {
                return $this->errorResponse('Please verify your phone number first', 403);
            }

            $token = $this->authService->createUserToken($user);

            return $this->successResponse([
                'user' => $user,
                'token' => $token
            ], 'Login successful');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during login', 500, ['error' => $e->getMessage()]);
        }
    }
}