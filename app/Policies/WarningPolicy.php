<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;
use App\Models\Warning;
use App\Services\SsoService;

class WarningPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('warning.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Warning $warning): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('warning.index') && $warning->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('warning.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Warning $warning): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('warning.edit') && $warning->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Warning $warning): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('warning.delete') && $warning->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can import students to the model.
     */
    public function import(User $user, Warning $warning): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('warning.import') && $warning->faculty_id === $userData['faculty_id'];
    }
}
