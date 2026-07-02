<?php

namespace App\Providers;

use App\Services\Otp\OtpChannel;
use App\Services\Otp\TelegramOtpChannel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // OTP kanali sifatida Telegram bot ishlatiladi (backend.md §5).
        // Kelajakda SMS gateway qo'shilsa, shu bindingni almashtirish kifoya.
        $this->app->bind(OtpChannel::class, TelegramOtpChannel::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
