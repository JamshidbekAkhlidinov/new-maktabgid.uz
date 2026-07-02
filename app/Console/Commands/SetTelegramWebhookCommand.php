<?php

namespace App\Console\Commands;

use App\Services\Telegram\TelegramBotService;
use Illuminate\Console\Command;

/**
 * Bir martalik sozlash komandasi: botni Telegram serverlariga webhook sifatida ro'yxatdan o'tkazadi.
 * APP_URL production'da https bo'lishi shart (Telegram http webhookni qabul qilmaydi).
 */
class SetTelegramWebhookCommand extends Command
{
    protected $signature = 'telegram:webhook:set';

    protected $description = 'MaktabGID Telegram botining webhookini APP_URL/telegram/webhook ga o\'rnatadi';

    public function handle(TelegramBotService $bot): int
    {
        if (! $bot->isConfigured()) {
            $this->error('TELEGRAM_BOT_TOKEN .env da sozlanmagan.');

            return self::FAILURE;
        }

        $secret = (string) config('services.telegram.webhook_secret');
        $url = rtrim(config('app.url'), '/').'/telegram/webhook';

        if (! str_starts_with($url, 'https://')) {
            $this->warn("Diqqat: Telegram faqat HTTPS webhookni qabul qiladi. Hozirgi APP_URL: {$url}");
        }

        $ok = $bot->setWebhook($url, $secret);

        if ($ok) {
            $this->info("Webhook o'rnatildi: {$url}");

            return self::SUCCESS;
        }

        $this->error('Webhook o\'rnatilmadi. Bot token va APP_URL ni tekshiring.');

        return self::FAILURE;
    }
}
