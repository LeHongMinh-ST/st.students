<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Role;
use App\Models\LogActivity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any activities.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        if ($user->role === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('activity.index');
    }

    /**
     * Determine whether the user can view the activity.
     *
     * @param User $user
     * @param LogActivity $activity
     * @return bool
     */
    public function view(User $user, LogActivity $activity): bool
    {
        if ($user->role === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('activity.view');
    }
}
