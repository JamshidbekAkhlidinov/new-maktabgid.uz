<?php

namespace App\Notifications\Channels;

use App\Models\TelegramLink;
use App\Services\Telegram\TelegramBotService;
use Illuminate\Notifications\Notification;

/**
 * Custom notification kanali — Telegram ulangan foydalanuvchilarga bot orqali xabar yuboradi.
 * Ulanmagan bo'lsa jim o'tkazib yuboradi ('database' kanal baribir yozib qoladi).
 */
class TelegramChannel
{
    public function __construct(protected TelegramBotService $bot) {}

    public function send(mixed $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toTelegram')) {
            return;
        }

        $phone = $notifiable->phone ?? null;

        if (! $phone) {
            return;
        }

        $link = TelegramLink::where('phone', $phone)->first();

        if (! $link) {
            return;
        }

        $text = $notification->toTelegram($notifiable);

        if ($text) {
            $this->bot->sendMessage($link->telegram_chat_id, $text);
        }
    }
}
