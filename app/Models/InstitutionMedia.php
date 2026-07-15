<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionMedia extends Model
{
    use HasFactory;

    protected $fillable = ['institution_id', 'type', 'disk', 'url', 'path', 'caption', 'duration', 'description', 'sort_order'];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
