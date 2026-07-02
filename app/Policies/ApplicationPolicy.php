<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function updateStatus(User $user, Application $application): bool
    {
        return $user->isAdmin() || $user->id === $application->institution?->owner_user_id;
    }
}
