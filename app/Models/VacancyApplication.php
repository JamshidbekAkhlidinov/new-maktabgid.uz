<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class VacancyApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'legacy_id', 'vacancy_id', 'teacher_user_id', 'full_name', 'phone', 'note',
        'resume_path', 'resume_disk', 'resume_original_name', 'status',
    ];

    protected function resumeUrl(): Attribute
    {
        return Attribute::get(fn () => $this->resume_path
            ? Storage::disk($this->resume_disk ?? config('filesystems.media_disk'))->url($this->resume_path)
            : null);
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_user_id');
    }
}
