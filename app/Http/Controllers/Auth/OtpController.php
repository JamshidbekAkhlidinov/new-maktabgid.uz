<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Otp\OtpService;
use App\Services\Telegram\TelegramBotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class OtpController extends Controller
{
    public function __construct(
        protected OtpService $otp,
        protected TelegramBotService $bot,
    ) {}

    /**
     * Telegram ulangan bo'lsa kod yuboradi; ulanmagan bo'lsa deep-link qaytaradi (§5, backend.md).
     */
    public function request(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string'],
            'purpose' => ['sometimes', 'in:register,login'],
        ]);

        $phone = $data['phone'];
        $purpose = $data['purpose'] ?? 'register';

        $key = 'otp-request:'.$phone;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json([
                'message' => 'Juda ko\'p urinish. Bir necha daqiqadan so\'ng qayta urining.',
            ], 429);
        }
        RateLimiter::hit($key, 60);

        if (! $this->otp->isChannelLinked($phone)) {
            return response()->json([
                'linked' => false,
                'telegramDeepLink' => $this->bot->deepLink(),
                'message' => 'Avval Telegram botimizga ulaning, so\'ng kodni qayta so\'rang.',
            ]);
        }

        $this->otp->issue($phone, $purpose);

        return response()->json(['linked' => true, 'sent' => true]);
    }

    public function verify(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string'],
            'code' => ['required', 'string'],
            'purpose' => ['sometimes', 'in:register,login'],
        ]);

        $ok = $this->otp->verify($data['phone'], $data['code'], $data['purpose'] ?? 'register');

        if (! $ok) {
            return response()->json([
                'verified' => false,
                'message' => 'Kod noto\'g\'ri yoki muddati o\'tgan.',
            ], 422);
        }

        User::where('phone', $data['phone'])->update(['phone_verified_at' => now()]);

        return response()->json(['verified' => true]);
    }
}
