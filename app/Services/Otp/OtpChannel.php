<?php

namespace App\Services\Otp;

interface OtpChannel
{
    /** Shu telefon uchun kanal orqali xabar yetkazish mumkinmi (masalan, Telegram ulangan bo'lishi kerak) */
    public function isAvailable(string $phone): bool;

    /** Kodni yuboradi, muvaffaqiyatli bo'lsa true qaytaradi */
    public function send(string $phone, string $code): bool;
}
