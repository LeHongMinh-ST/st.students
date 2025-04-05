<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Role;
use App\Helpers\StudentHelper;
use App\Models\Student;
use App\Models\User;
use App\Services\SsoService;

class StudentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('student.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Student $student): bool
    {
        return StudentHelper::checkUserStudent($user, $student) || $user->hasPermission('student.show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('student.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Student $student): bool
    {
        $userData = app(SsoService::class)->getDataUser();
        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('student.edit') && $student->faculty_id === $user['faculty_id'];
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Student $student): bool
    {
        $userData = app(SsoService::class)->getDataUser();
        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('student.delete') && $student->faculty_id === $user['faculty_id'];
    }
}
