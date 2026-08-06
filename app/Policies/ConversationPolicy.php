<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

/**
 * Suhbatni faqat ishtirokchilar ko'ra oladi — ota-ona/ustoz (qaysi tomon bo'lsa)
 * yoki muassasa egasi (ko'p-filial hisobga olinadi, User::institutions()). ADR-0003.
 */
class ConversationPolicy
{
    public function view(User $user, Conversation $conversation): bool
    {
        if ($user->isParent()) {
            return $conversation->parent_user_id === $user->id;
        }

        if ($user->isTeacher()) {
            return $conversation->teacher_user_id === $user->id;
        }

        if ($user->isInstitution()) {
            return $user->institutions()->whereKey($conversation->institution_id)->exists();
        }

        return $user->isAdmin();
    }
}
