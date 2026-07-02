<?php

namespace App\Services\Otp;

use App\Models\TelegramLink;
use App\Services\Telegram\TelegramBotService;

/**
 * OTP kodlarni SMS gateway o'rniga Telegram bot orqali yuboradi.
 * Foydalanuvchi avval botga ulanган (telefon raqamini ulashgan) bo'lishi shart —
 * aks holda isAvailable() false qaytaradi va frontend deep-link ko'rsatadi (§5, backend.md).
 */
class TelegramOtpChannel implements OtpChannel
{
    public function __construct(protected TelegramBotService $bot) {}

    public function isAvailable(string $phone): bool
    {
        return TelegramLink::where('phone', $phone)->exists();
    }

    public function send(string $phone, string $code): bool
    {
        $link = TelegramLink::where('phone', $phone)->first();

        if (! $link) {
            return false;
        }

        return $this->bot->sendMessage(
            $link->telegram_chat_id,
            "🔐 MaktabGID tasdiqlash kodi: <b>{$code}</b>\n\nKod 5 daqiqa amal qiladi. Bu kodni hech kimga bermang."
        );
    }
}
