<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\AuthUserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt(['phone' => $credentials['phone'], 'password' => $credentials['password']])) {
            return response()->json([
                'message' => 'Telefon raqami yoki parol noto\'g\'ri.',
            ], 422);
        }

        $request->session()->regenerate();

        $user = $request->user();
        $user->forceFill(['last_login_at' => now()])->save();

        return response()->json(['user' => new AuthUserResource($user->load(['district', 'institution']))]);
    }
}
