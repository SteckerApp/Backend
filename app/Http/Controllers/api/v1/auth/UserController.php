<?php

namespace App\Http\Controllers\api\v1\auth;

use Illuminate\Http\Request;
use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Requests\auth\VerifyEmailRequest;
use App\Http\Requests\auth\PasswordResetRequest;
use App\Http\Requests\auth\ChangePasswordRequest;
use App\Trait\HandleResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use HandleResponse;
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        return $this->authService->createUser($request);
    }

    public function resendVerification(Request $request)
    {
        return $this->authService->resendVerification($request);
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        return $this->authService->verifyEmail($request);
    }

    public function passwordReset(PasswordResetRequest $request)
    {
        return $this->authService->passwordReset($request);
    }

    public function resendPasswordReset(PasswordResetRequest $request)
    {
        return $this->authService->resendPasswordReset($request);
    }


    public function changePassword(ChangePasswordRequest $request)
    {
        return $this->authService->changePassword($request);
    }

    public function login(LoginRequest $request)
    {
        return $this->authService->login($request);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }

    public function profile(Request $request)
    {
        return $request->user();
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'password' => 'sometimes|string'
        ]);

        $user = $request->user();

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->fill($request->only([
            'first_name',
            'last_name',
            'name',
            'phone_number',
            'currency',
        ]));

        $user->update();

        return $this->successResponse(null, 'User profile updated successfully');
    }
}
