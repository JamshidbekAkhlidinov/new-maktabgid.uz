<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id', 'user_id', 'rating', 'body',
        // Eski (Yii2) telegram-bot izohlari — ro'yxatdan o'tmagan mehmondan (LegacyReviewSeeder).
        'guest_name', 'legacy_comment_id', 'legacy_rate_id',
    ];

    /** Ko'rsatiladigan muallif ismi — ro'yxatdan o'tgan bo'lsa profildan, aks holda mehmon ismi. */
    public function authorName(): string
    {
        return $this->author?->name ?? $this->guest_name ?? 'Mehmon';
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
