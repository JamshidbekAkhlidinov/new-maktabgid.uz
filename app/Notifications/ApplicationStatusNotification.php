<?php

namespace App\Notifications;

use App\Models\Application;
use App\Notifications\Channels\TelegramChannel;
use Illuminate\Notifications\Notification;

/** Ota-onaga — ariza holati (tasdiqlandi/rad etildi) o'zgarganda. */
class ApplicationStatusNotification extends Notification
{
    public function __construct(protected Application $application) {}

    public function via(mixed $notifiable): array
    {
        return ['database', TelegramChannel::class];
    }

    public function toDatabase(mixed $notifiable): array
    {
        return [
            'title' => 'Ariza holati o\'zgardi',
            'application_id' => $this->application->id,
            'status' => $this->application->status,
        ];
    }

    public function toTelegram(mixed $notifiable): ?string
    {
        $a = $this->application;

        $label = match ($a->status) {
            'confirmed' => '✅ Arizangiz tasdiqlandi!',
            'rejected' => '❌ Arizangiz rad etildi.',
            'completed' => '🎉 Tashrifingiz uchun rahmat!',
            default => null,
        };

        if (! $label) {
            return null;
        }

        return "{$label}\n\n{$a->institution?->name} — {$a->child_name}";
    }
}
