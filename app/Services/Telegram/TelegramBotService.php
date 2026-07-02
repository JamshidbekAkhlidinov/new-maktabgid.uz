<?php

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Telegram Bot API bilan to'g'ridan-to'g'ri ishlaydi (HTTP orqali) — tashqi SDK kerak emas.
 * https://core.telegram.org/bots/api
 */
class TelegramBotService
{
    protected string $token;

    protected string $apiBase;

    public function __construct()
    {
        $this->token = (string) config('services.telegram.bot_token');
        $this->apiBase = "https://api.telegram.org/bot{$this->token}";
    }

    public function isConfigured(): bool
    {
        return filled($this->token);
    }

    /**
     * Botning start-link manzili (masalan telefon ulanmagan foydalanuvchiga ko'rsatish uchun).
     */
    public function deepLink(): ?string
    {
        $username = config('services.telegram.bot_username');

        return $username ? "https://t.me/{$username}?start=link" : null;
    }

    public function sendMessage(string|int $chatId, string $text, ?array $replyMarkup = null): bool
    {
        if (! $this->isConfigured()) {
            Log::warning('Telegram bot token sozlanmagan — xabar yuborilmadi.', ['chat_id' => $chatId]);

            return false;
        }

        $payload = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        if ($replyMarkup) {
            $payload['reply_markup'] = json_encode($replyMarkup);
        }

        $response = Http::asForm()->post("{$this->apiBase}/sendMessage", $payload);

        if (! $response->successful()) {
            Log::warning('Telegram sendMessage muvaffaqiyatsiz.', [
                'chat_id' => $chatId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return $response->successful();
    }

    /** "📱 Telefon raqamni ulashish" tugmasi bilan /start javobi */
    public function sendContactRequest(string|int $chatId): bool
    {
        return $this->sendMessage(
            $chatId,
            "Assalomu alaykum! 👋\n\nMaktabGID platformasida tasdiqlash kodlarini (OTP) shu bot orqali olish uchun telefon raqamingizni ulashing.",
            [
                'keyboard' => [[
                    ['text' => '📱 Telefon raqamni ulashish', 'request_contact' => true],
                ]],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]
        );
    }

    /**
     * Webhookni Telegram serverlarida ro'yxatdan o'tkazadi (deploy/artisan komandada bir marta chaqiriladi).
     */
    public function setWebhook(string $url, string $secret): bool
    {
        $response = Http::asForm()->post("{$this->apiBase}/setWebhook", [
            'url' => $url,
            'secret_token' => $secret,
            'allowed_updates' => json_encode(['message']),
        ]);

        return $response->successful();
    }
}
