<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Role;
use App\Models\TrainingIndustry;
use App\Models\User;
use App\Services\SsoService;

class TrainingIndustryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('training_industry.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TrainingIndustry $trainingIndustry): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('training_industry.index') && $trainingIndustry->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('training_industry.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TrainingIndustry $trainingIndustry): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('training_industry.edit') && $trainingIndustry->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TrainingIndustry $trainingIndustry): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('training_industry.delete') && $trainingIndustry->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TrainingIndustry $trainingIndustry): bool
    {
        return $user->hasPermission('training_industry.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TrainingIndustry $trainingIndustry): bool
    {
        return $user->hasPermission('training_industry.force_delete');
    }
}
