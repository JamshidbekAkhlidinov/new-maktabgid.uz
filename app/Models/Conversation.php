<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    // parent_user_id VA teacher_user_id — aynan bittasi to'ldiriladi (ADR-0003).
    // Ilova darajasida ConversationController@start shuni ta'minlaydi (DB darajasida
    // ikkalasi ham nullable, faqat institution_id bilan alohida-alohida unique).
    protected $fillable = ['parent_user_id', 'teacher_user_id', 'institution_id', 'last_message_at'];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    /** Ustoz tomoni — faqat teacher_user_id to'ldirilgan suhbatlarda (ADR-0003). */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_user_id');
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Institution tomonidan ko'riladigan "suhbatdosh" — parent yoki teacher, qaysi
     * biri to'ldirilgan bo'lsa o'shani qaytaradi. UI'da generic ko'rsatish uchun
     * (institution/conversations.blade.php).
     */
    protected function participant(): Attribute
    {
        return Attribute::get(fn () => $this->parent ?? $this->teacher);
    }

    /** UI badge uchun — "Ota-ona" yoki "Ustoz". */
    protected function participantRole(): Attribute
    {
        return Attribute::get(fn () => $this->parent_user_id ? 'parent' : 'teacher');
    }
}
