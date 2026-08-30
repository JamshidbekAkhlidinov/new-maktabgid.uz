<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** Reklama bannerlari (bosh sahifa/katalog) — eski `advertisement` jadvalidan (LegacyAdvertisementSeeder). */
class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'legacy_id', 'image_url', 'disk', 'image_path', 'link_url',
        'is_active', 'started_at', 'finished_at',
        'badge', 'tag', 'initials', 'title', 'rating', 'description', 'cta_label',
    ];

    /**
     * `image_url` ustuni hali `NOT NULL` (doctrine/dbal o'rnatilmagani uchun
     * migratsiyada nullable qilib o'zgartirilmagan) — matnli/gradient dizaynda
     * esa umuman ishlatilmaydi, shu sababli standart bo'sh qator beriladi.
     */
    protected $attributes = [
        'image_url' => '',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    /** Hozir ko'rsatilishi kerakmi — faol va sana oralig'ida (agar belgilangan bo'lsa). */
    public function isCurrentlyRunning(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now();

        if ($this->started_at && $now->lt($this->started_at)) {
            return false;
        }

        if ($this->finished_at && $now->gt($this->finished_at)) {
            return false;
        }

        return true;
    }
}
