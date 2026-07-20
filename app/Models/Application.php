<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id', 'parent_user_id', 'type', 'child_name', 'child_birth_date',
        'child_age', 'current_grade', 'target_grade', 'previous_school',
        'parent_name', 'parent_phone', 'preferred_start', 'scheduled_at', 'note', 'status',
    ];

    protected function casts(): array
    {
        return [
            'child_birth_date' => 'date',
            'scheduled_at' => 'datetime',
        ];
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }
}
