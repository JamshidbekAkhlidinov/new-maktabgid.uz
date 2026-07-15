<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Ota-ona kabineti — "Farzandlarim" (AI Tanlovchi uchun farzand profili).
 * ParentCabinetController::context()'dagi eski $mockChildren massivi o'rniga
 * real jadval (ADR: farzand qo'shish/tahrirlash/o'chirish, 2026-07-14).
 */
class Child extends Model
{
    public const GENDER_BOY = 'ogil';

    public const GENDER_GIRL = 'qiz';

    protected $fillable = [
        'parent_user_id', 'name', 'last_name', 'age', 'gender', 'interests',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'interests' => 'array',
        ];
    }

    public function parentUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }
}
