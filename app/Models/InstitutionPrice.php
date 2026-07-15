<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Muassasa kabineti — "Narxlar" jadvali qatori (sinf/guruh + o'quv tili bo'yicha
 * alohida oylik narx va chegirma). Institution::prices() orqali ishlatiladi;
 * Institution::monthly_price shular ichidan eng kichigi bilan avtomatik
 * yangilanadi (Institution\ProfileController::update(), 2026-07-15).
 */
class InstitutionPrice extends Model
{
    protected $fillable = [
        'institution_id', 'grade', 'lang', 'monthly_price', 'discount', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'monthly_price' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
