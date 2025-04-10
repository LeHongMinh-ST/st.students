<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Role;
use App\Models\GraduationCeremony;
use App\Models\User;
use App\Services\SsoService;

class GraduationCeremonyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('graduation.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GraduationCeremony $ceremony): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('graduation.index') && $ceremony->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('graduation.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GraduationCeremony $ceremony): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('graduation.edit') && $ceremony->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GraduationCeremony $ceremony): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('graduation.delete') && $ceremony->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can import students to the model.
     */
    public function import(User $user, GraduationCeremony $ceremony): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('graduation.import') && $ceremony->faculty_id === $userData['faculty_id'];
    }
}
