<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Role;
use App\Models\Quit;
use App\Models\User;
use App\Services\SsoService;

class QuitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('quit.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Quit $quit): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('quit.index') && $quit->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('quit.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Quit $quit): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('quit.edit') && $quit->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Quit $quit): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('quit.delete') && $quit->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can import students to the model.
     */
    public function import(User $user, Quit $quit): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('quit.import') && $quit->faculty_id === $userData['faculty_id'];
    }
}
