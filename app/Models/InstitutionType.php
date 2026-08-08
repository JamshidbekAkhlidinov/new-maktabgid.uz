<?php

namespace App\Models;

use App\Support\Concerns\HasTranslatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstitutionType extends Model
{
    use HasFactory;
    use HasTranslatable;

    protected $fillable = ['key', 'label', 'icon', 'is_active'];

    /** Uch tillilik (2026-08-08) — {"uz":..,"ru":..,"en":..}. */
    protected array $translatable = ['label'];

    protected function casts(): array
    {
        return [
            'label' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class, 'type', 'key');
    }
}
