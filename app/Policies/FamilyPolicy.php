<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Role;
use App\Models\Family;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FamilyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any families.
     *
     * @param User $user
     * @param Student $student
     * @return bool
     */
    public function viewAny(User $user, Student $student): bool
    {
        if ($user->role === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('family.viewAny');
    }

    /**
     * Determine whether the user can view the family.
     *
     * @param User $user
     * @param Family $family
     * @return bool
     */
    public function view(User $user, Family $family): bool
    {
        if ($user->role === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('family.view');
    }

    /**
     * Determine whether the user can create families.
     *
     * @param User $user
     * @param Student $student
     * @return bool
     */
    public function create(User $user, Student $student): bool
    {
        if ($user->role === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('family.create');
    }

    /**
     * Determine whether the user can update the family.
     *
     * @param User $user
     * @param Family $family
     * @return bool
     */
    public function update(User $user, Family $family): bool
    {
        if ($user->role === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('family.update');
    }

    /**
     * Determine whether the user can delete the family.
     *
     * @param User $user
     * @param Family $family
     * @return bool
     */
    public function delete(User $user, Family $family): bool
    {
        if ($user->role === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('family.delete');
    }
}
