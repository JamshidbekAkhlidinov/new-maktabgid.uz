<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->hasRole('Super Admin')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email yoki parol noto\'g\'ri.',
            ])->onlyInput('email');
        }

        $user = Auth::user();

        if (! $user->hasRole('Super Admin')) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Admin panelga faqat Super Admin huquqiga ega foydalanuvchilar kira oladi.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        $user->forceFill(['last_login_at' => now()])->save();

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Google OAuth oynasiga yo'naltirish.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Google'dan qaytgan callback. Faqat allaqachon mavjud va "Super Admin"
     * roliga ega foydalanuvchi (email bo'yicha topiladi) kira oladi — Google
     * orqali yangi hisob avtomatik yaratilmaydi.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (Throwable $e) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Google orqali kirishda xatolik yuz berdi. Qaytadan urinib ko\'ring.',
            ]);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user || ! $user->hasRole('Super Admin')) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Bu Google hisob admin panelga ega emas yoki Super Admin huquqiga ega emas.',
            ]);
        }

        if (! $user->google_id) {
            $user->forceFill(['google_id' => $googleUser->getId()])->save();
        }

        Auth::login($user, true);
        $request->session()->regenerate();
        $user->forceFill(['last_login_at' => now()])->save();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
