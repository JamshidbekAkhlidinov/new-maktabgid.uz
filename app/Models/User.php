<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'phone', 'email', 'password', 'role', 'age', 'district_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Spatie\Permission — admin panelidagi dinamik rol/permission tekshiruvlari.
    // Diqqat: mavjud 'role' ustuni (parent|institution|admin) saytning asosiy rolini
    // bildiradi va o'zgarishsiz qoladi; HasRoles esa shunga QO'SHIMCHA — admin panel
    // ichidagi granular huquqlarni (Spatie roles/permissions) boshqaradi.
    use HasRoles;

    public const ROLE_PARENT = 'parent';

    public const ROLE_INSTITUTION = 'institution';

    public const ROLE_ADMIN = 'admin';

    public const ROLE_TEACHER = 'teacher';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isParent(): bool
    {
        return $this->role === self::ROLE_PARENT;
    }

    public function isInstitution(): bool
    {
        return $this->role === self::ROLE_INSTITUTION;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isTeacher(): bool
    {
        return $this->role === self::ROLE_TEACHER;
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /** Muassasa (agar role=institution bo'lsa) */
    public function institution(): HasOne
    {
        return $this->hasOne(Institution::class, 'owner_user_id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'parent_user_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'parent_user_id');
    }

    public function forumThreads(): HasMany
    {
        return $this->hasMany(ForumThread::class);
    }

    public function resumes(): HasMany
    {
        return $this->hasMany(Resume::class, 'owner_user_id');
    }
}
