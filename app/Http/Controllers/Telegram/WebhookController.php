<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\TelegramLink;
use App\Services\Telegram\TelegramBotService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Telegram bot yuboradigan har bir update shu yerga POST qilinadi (setWebhook orqali sozlanadi).
 * Faqat ikkita holatni qayta ishlaydi: /start (klaviatura ko'rsatish) va contact ulashish
 * (telefon raqamni chat_id bilan bog'lash) — §5, backend.md.
 */
class WebhookController extends Controller
{
    public function __construct(protected TelegramBotService $bot) {}

    public function __invoke(Request $request): Response
    {
        $secret = config('services.telegram.webhook_secret');

        if (filled($secret) && $request->header('X-Telegram-Bot-Api-Secret-Token') !== $secret) {
            abort(403);
        }

        $message = $request->input('message');

        if (! $message) {
            return response()->noContent();
        }

        $chatId = $message['chat']['id'] ?? null;
        $contact = $message['contact'] ?? null;
        $text = $message['text'] ?? null;

        if ($chatId && $contact && isset($contact['phone_number'])) {
            $phone = $this->normalizePhone($contact['phone_number']);

            TelegramLink::updateOrCreate(
                ['phone' => $phone],
                [
                    'telegram_chat_id' => (string) $chatId,
                    'telegram_username' => $message['from']['username'] ?? null,
                    'linked_at' => now(),
                ]
            );

            $this->bot->sendMessage($chatId, '✅ Raqamingiz ulandi! Endi saytga qaytib, tasdiqlash kodini qayta so\'rashingiz mumkin.');
        } elseif ($chatId && $text === '/start') {
            $this->bot->sendContactRequest($chatId);
        }

        return response()->noContent();
    }

    protected function normalizePhone(string $phone): string
    {
        return '+'.preg_replace('/\D+/', '', $phone);
    }
}
