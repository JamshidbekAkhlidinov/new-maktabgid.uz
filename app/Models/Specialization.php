<?php

namespace App\Models;

use App\Support\Concerns\HasTranslatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Specialization extends Model
{
    use HasFactory;
    use HasTranslatable;

    protected $fillable = ['key', 'label', 'icon'];

    /** Uch tillilik (2026-08-06) — {"uz":..,"ru":..,"en":..}. */
    protected array $translatable = ['label'];

    protected function casts(): array
    {
        return [
            'label' => 'array',
        ];
    }

    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'institution_specialization');
    }
}
