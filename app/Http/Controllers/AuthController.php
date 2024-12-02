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

    /**
     * User Registration
     *
     * Daftar pengguna baru dengan nama, nomor telepon, email, dan kata sandi. Verifikasi kode akan dikirim ke nomor telepon.
     *
     * @group Authentication
     * @bodyParam name string required Nama pengguna. Example: John Doe
     * @bodyParam phone string required Nomor telepon dalam format internasional. Example: +1234567890
     * @bodyParam email string required Alamat email pengguna. Example: john.doe@example.com
     * @bodyParam password string required Kata sandi untuk akun. Example: password123
     * @bodyParam password_confirmation string required Konfirmasi kata sandi. Example: password123
     * @response 201 {
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "phone": "+1234567890",
     *     "email": "john.doe@example.com"
     *   },
     *   "verification_code": "123456",
     *   "message": "A verification code has been sent to your phone."
     * }
     * @response 422 {
     *   "message": "Validation failed",
     *   "errors": {
     *     "phone": ["The phone field is required."]
     *   }
     * }
     */
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

    /**
     * Verify Phone Number
     *
     * Verifikasi nomor telepon pengguna menggunakan kode verifikasi yang dikirim.
     *
     * @group Authentication
     * @bodyParam phone string required Nomor telepon pengguna. Example: +1234567890
     * @bodyParam code string required Kode verifikasi 6 digit. Example: 123456
     * @response 200 {
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "phone": "+1234567890",
     *     "is_verified": true
     *   },
     *   "token": "eyJhbGciOiJIUzI1NiIsInR5c..."
     * }
     * @response 422 {
     *   "message": "Invalid or expired verification code"
     * }
     * @response 404 {
     *   "message": "User not found"
     * }
     */
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

    /**
     * User Login
     *
     * Masuk dengan nomor telepon dan kata sandi pengguna.
     *
     * @group Authentication
     * @bodyParam phone string required Nomor telepon pengguna yang terdaftar. Example: +1234567890
     * @bodyParam password string required Kata sandi akun pengguna. Example: password123
     * @response 200 {
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "phone": "+1234567890",
     *     "email": "john.doe@example.com"
     *   },
     *   "token": "eyJhbGciOiJIUzI1NiIsInR5c..."
     * }
     * @response 401 {
     *   "message": "Invalid login credentials"
     * }
     */
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

    /**
     * User Logout
     *
     * Keluar dari sistem dengan menghapus token akses pengguna.
     *
     * @group Authentication
     * @authenticated
     * @response 200 {
     *   "message": "Logged out successfully"
     * }
     * @response 500 {
     *   "message": "An error occurred during logout."
     * }
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->successResponse(null, 'Logged out successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during logout.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Request Password Reset
     *
     * Kirim kode verifikasi untuk mengatur ulang kata sandi pengguna.
     *
     * @group Authentication
     * @bodyParam phone string required Nomor telepon pengguna yang terdaftar. Example: +1234567890
     * @response 200 {
     *   "message": "Password reset verification code sent",
     *   "verification_code": "123456"
     * }
     * @response 404 {
     *   "message": "User not found"
     * }
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $user = User::where('phone', $request->phone)->firstOrFail();

            $code = $this->authService->resetPassword($user);

            return $this->successResponse([
                'message' => 'Password reset verification code sent',
                'verification_code' => $code
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during password reset.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Set New Password
     *
     * Atur ulang kata sandi pengguna menggunakan kode verifikasi.
     *
     * @group Authentication
     * @bodyParam phone string required Nomor telepon pengguna. Example: +1234567890
     * @bodyParam code string required Kode verifikasi 6 digit. Example: 123456
     * @bodyParam password string required Kata sandi baru. Example: newpassword123
     * @bodyParam password_confirmation string required Konfirmasi kata sandi baru. Example: newpassword123
     * @response 200 {
     *   "message": "Password reset successfully"
     * }
     * @response 422 {
     *   "message": "Invalid or expired verification code"
     * }
     */
    public function setNewPassword(SetNewPasswordRequest $request)
    {
        try {
            $user = User::where('phone', $request->phone)->firstOrFail();

            if (!$this->authService->setNewPassword($user, $request->code, $request->password)) {
                return $this->errorResponse('Invalid or expired verification code', 422);
            }

            return $this->successResponse(null, 'Password reset successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during password reset.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get User Profile
     *
     * Ambil detail profil pengguna yang sedang masuk.
     *
     * @group User Management
     * @authenticated
     * @response 200 {
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "phone": "+1234567890",
     *     "email": "john.doe@example.com"
     *   }
     * }
     */
    public function profile(Request $request)
    {
        return $this->successResponse(['user' => $request->user()], 'User profile');
    }

    /**
     * Update User Profile
     *
     * Perbarui informasi profil pengguna.
     *
     * @group User Management
     * @authenticated
     * @bodyParam name string required Nama baru pengguna. Example: Jane Doe
     * @response 200 {
     *   "message": "Profile updated successfully",
     *   "user": {
     *     "id": 1,
     *     "name": "Jane Doe",
     *     "phone": "+1234567890",
     *     "email": "john.doe@example.com"
     *   }
     * }
     * @response 422 {
     *   "message": "Validation failed",
     *   "errors": {
     *     "name": ["The name field is required."]
     *   }
     * }
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        try {
            $user = $this->authService->updateProfile($request->user(), $request->only('name'));

            return $this->successResponse([
                'message' => 'Profile updated successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while updating profile.', 500, ['error' => $e->getMessage()]);
        }
    }
}