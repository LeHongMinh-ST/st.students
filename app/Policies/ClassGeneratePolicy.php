<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ClassGenerate;
use App\Models\User;

class ClassGeneratePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('class.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClassGenerate $class): bool
    {
        return $user->hasPermission('class.index');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('class.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClassGenerate $class): bool
    {
        return $user->hasPermission('class.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClassGenerate $class): bool
    {
        return $user->hasPermission('class.delete');
    }

    /**
     * Determine whether the user can manage class teachers.
     */
    public function manageTeacher(User $user): bool
    {
        return $user->hasPermission('class.teacher');
    }

    /**
     * Determine whether the user can manage class sub-teachers.
     */
    public function manageSubTeacher(User $user): bool
    {
        return $user->hasPermission('class.sub_teacher');
    }

    /**
     * Determine whether the user can manage teacher assignments.
     */
    public function manageTeacherAssignment(User $user): bool
    {
        return $user->hasPermission('class.teacher_assignment');
    }
}
