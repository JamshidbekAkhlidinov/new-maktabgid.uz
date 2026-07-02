<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'label', 'icon'];

    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'institution_specialization');
    }
}
