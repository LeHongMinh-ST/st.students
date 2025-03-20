<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('users.index');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function assignRole(User $user): bool
    {
        return $user->hasPermission('users.assign-role');
    }

}
