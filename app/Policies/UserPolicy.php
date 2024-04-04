<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function hasPermission(User $user, $permission)
    {
        return $user->hasPermission($permission);
    }
}
