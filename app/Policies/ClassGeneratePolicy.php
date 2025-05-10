<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ClassGenerate;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        // Superadmin hoặc người dùng có quyền xem danh sách lớp
        if ($user->isSuperAdmin() || $user->hasPermission('class.index')) {
            return true;
        }

        // Giáo viên chủ nhiệm của lớp
        if ($user->hasPermission('class.teacher')) {
            $isTeacher = DB::table('class_assigns')
                ->where('class_id', $class->id)
                ->where('teacher_id', $user->id)
                ->where('status', \App\Enums\Status::Active->value)
                ->exists();

            if ($isTeacher) {
                return true;
            }
        }

        // Sinh viên của lớp
        if ($user->isStudent()) {
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            if ($student) {
                $isInClass = DB::table('class_students')
                    ->where('class_id', $class->id)
                    ->where('student_id', $student->id)
                    ->exists();

                if ($isInClass) {
                    return true;
                }
            }
        }

        return false;
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

    /**
     * Determine whether the user can manage specialized class transfers.
     */
    public function manageSpecializedTransfer(User $user): bool
    {
        return $user->hasPermission('class.specialized_transfer');
    }
}
