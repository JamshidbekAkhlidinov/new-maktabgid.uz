<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'role_title', 'experience', 'specialization_key',
        'salary_expectation', 'district_id', 'languages', 'owner_user_id',
        'phone', 'education', 'skills', 'description',
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }
}
