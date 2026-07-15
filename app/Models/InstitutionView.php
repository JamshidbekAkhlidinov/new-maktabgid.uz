<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Bitta muassasa profili ko'rishi — /maktab/{id} sahifasi ochilganda yoziladi
 * (ADR-0002, Faza 2). Institution kabinetidagi "Analitika" sahifasi va
 * dashboard'dagi "bugun necha marta ko'rildi" shu jadvaldan hisoblanadi.
 */
class InstitutionView extends Model
{
    public $timestamps = false;

    protected $fillable = ['institution_id', 'viewer_user_id', 'created_at'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function viewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewer_user_id');
    }
}
