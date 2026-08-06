<?php

namespace App\Providers;

use App\Models\ForumLike;
use App\Models\Review;
use App\Observers\ForumLikeObserver;
use App\Observers\ReviewObserver;
use App\Services\Otp\OtpChannel;
use App\Services\Otp\TelegramOtpChannel;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
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
        // Super Admin — admin panelidagi BARCHA (shu jumladan kelajakda qo'shiladigan)
        // huquqlarga avtomatik ega bo'ladi, har birini qo'lda syncPermissions qilish shart emas.
        Gate::before(function ($user, string $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Institution.rating/review_count har bir Review CRUD amalidan so'ng
        // avtomatik qayta hisoblanadi (ADR-0002).
        Review::observe(ReviewObserver::class);

        // Forum mavzu/javob like_count'i har bir ForumLike CRUD amalidan so'ng
        // avtomatik qayta hisoblanadi (ADR-0002, Faza 2).
        ForumLike::observe(ForumLikeObserver::class);

        // Chat xabar yuborish — spamga qarshi cheklov (ADR-0003, Faza D).
        RateLimiter::for('chat-message', function (Request $request) {
            return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip());
        });
    }
}
