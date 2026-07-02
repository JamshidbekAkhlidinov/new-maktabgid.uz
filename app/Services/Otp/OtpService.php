<?php

namespace App\Services\Otp;

use App\Models\OtpCode;

class OtpService
{
    public function __construct(protected OtpChannel $channel) {}

    public function isChannelLinked(string $phone): bool
    {
        return $this->channel->isAvailable($phone);
    }

    /**
     * Yangi OTP kod generatsiya qilib, kanalga (Telegram) yuboradi.
     */
    public function issue(string $phone, string $purpose = 'register'): OtpCode
    {
        $code = (string) random_int(100000, 999999);

        $otp = OtpCode::create([
            'phone' => $phone,
            'code' => $code,
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(5),
        ]);

        $this->channel->send($phone, $code);

        return $otp;
    }

    /**
     * Kodni tekshiradi. To'g'ri bo'lsa true va shu OTP yozuvini "verified" qiladi.
     */
    public function verify(string $phone, string $code, string $purpose = 'register'): bool
    {
        $otp = OtpCode::where('phone', $phone)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->latest('id')
            ->first();

        if (! $otp || $otp->isExpired() || $otp->attempts >= 5) {
            return false;
        }

        if (! hash_equals($otp->code, $code)) {
            $otp->increment('attempts');

            return false;
        }

        $otp->update(['verified_at' => now()]);

        return true;
    }
}
