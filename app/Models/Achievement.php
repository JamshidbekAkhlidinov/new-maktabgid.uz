<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Muassasa kabinetida qo'shiladigan o'quvchi yutug'i — profil sahifasida
 * ota-onalar uchun ishonch belgisi sifatida ko'rinadi (ADR-0002, Faza 2).
 */
class Achievement extends Model
{
    protected $fillable = [
        'institution_id', 'title', 'student_name', 'year', 'type', 'level',
        'disk', 'image_path', 'image_url',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
        ];
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
