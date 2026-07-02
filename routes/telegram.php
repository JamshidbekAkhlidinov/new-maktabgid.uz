<?php

use App\Http\Controllers\Telegram\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Telegram webhook
|--------------------------------------------------------------------------
| Ataylab 'web' guruhiga kiritilmagan — CSRF/session kerak emas (Telegram
| tashqi serverdan POST qiladi). Himoya X-Telegram-Bot-Api-Secret-Token
| headeri orqali (WebhookController, TELEGRAM_WEBHOOK_SECRET).
*/

Route::post('/telegram/webhook', WebhookController::class)->name('telegram.webhook');
