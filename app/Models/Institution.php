<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_user_id', 'name', 'type', 'about', 'lang', 'district_id',
        'address', 'lat', 'lng', 'monthly_price', 'grades', 'work_hours',
        'works_saturday', 'accepting', 'rating', 'review_count', 'badge',
        'facilities', 'teachers', 'programs', 'lessons', 'videos', 'admission_steps',
        'stat_class_size', 'stat_experience_years', 'stat_admission_rate', 'stat_first_grade_seats',
    ];

    protected function casts(): array
    {
        return [
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
            'works_saturday' => 'boolean',
            'accepting' => 'boolean',
            'rating' => 'decimal:1',
            'facilities' => 'array',
            'teachers' => 'array',
            'programs' => 'array',
            'lessons' => 'array',
            'videos' => 'array',
            'admission_steps' => 'array',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function specializations(): BelongsToMany
    {
        return $this->belongsToMany(Specialization::class, 'institution_specialization');
    }

    public function media(): HasMany
    {
        return $this->hasMany(InstitutionMedia::class)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class);
    }

    /** Real profil ko'rishlar jurnali — "Analitika" sahifasi shu yerdan hisoblanadi (ADR-0002, Faza 2). */
    public function views(): HasMany
    {
        return $this->hasMany(InstitutionView::class);
    }

    /** O'quvchilar yutuqlari — kabinetda qo'shiladi, ommaviy profilda ko'rinadi (ADR-0002, Faza 2). */
    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class)->latest();
    }
}
