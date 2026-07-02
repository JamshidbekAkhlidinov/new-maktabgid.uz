<?php

namespace App\Policies;

use App\Models\Institution;
use App\Models\User;

class InstitutionPolicy
{
    public function update(User $user, Institution $institution): bool
    {
        return $user->isAdmin() || $user->id === $institution->owner_user_id;
    }

    public function view(User $user, Institution $institution): bool
    {
        return $this->update($user, $institution);
    }
}
