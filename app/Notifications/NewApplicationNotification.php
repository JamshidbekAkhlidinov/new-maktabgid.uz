<?php

namespace App\Notifications;

use App\Models\Application;
use App\Notifications\Channels\TelegramChannel;
use Illuminate\Notifications\Notification;

/** Muassasa egasiga — yangi ariza kelganda. */
class NewApplicationNotification extends Notification
{
    public function __construct(protected Application $application) {}

    public function via(mixed $notifiable): array
    {
        return ['database', TelegramChannel::class];
    }

    public function toDatabase(mixed $notifiable): array
    {
        return [
            'title' => 'Yangi ariza',
            'application_id' => $this->application->id,
            'institution_id' => $this->application->institution_id,
            'child_name' => $this->application->child_name,
            'type' => $this->application->type,
        ];
    }

    public function toTelegram(mixed $notifiable): string
    {
        $a = $this->application;
        $kind = $a->type === 'excursion' ? 'Ekskursiya' : 'Joylashtirish';

        return "📩 Yangi ariza!\n\n{$kind} arizasi: <b>{$a->child_name}</b>\n"
            ."Ota-ona: {$a->parent_name}, {$a->parent_phone}\n\n"
            .'Kabinetda ko\'ring: '.rtrim(config('app.url'), '/').'/institution-cabinet';
    }
}
