<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Enums\Role;
use App\Models\Student;
use App\Models\User;
use App\Services\SsoService;

class StudentHelper
{
    /**
     * Generate the email address for a student based on their unique code.
     *
     * This function appends the student's unique code to a predefined email domain
     * specified in the configuration file under 'vnua.mail_student'.
     *
     * @param string $code The unique code assigned to the student.
     * @return string The generated email address for the student.
     */
    public static function makeEmailStudent(string $code): string
    {
        return $code . config('vnua.mail_student');
    }

    public static function checkUserStudent(User $user, Student $student): bool
    {
        $userData = app(SsoService::class)->getDataUser();
        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        if ($user->hasPermission('class.teacher')) {
            return static::isTeacher($user->id, $student->currentClass) && $user['faculty_id'] === $student->faculty_id;
        }

        if ($user->hasPermission('class.sub_teacher')) {
            return static::isSubTeacher($user->id, $student->currentClass) && $user['faculty_id'] === $student->faculty_id;
        }

        return false;
    }

    private static function isTeacher(int|string $id, $classe): bool
    {
        return $classe->teacher_id === $id;
    }

    private static function isSubTeacher(int|string $id, $class): bool
    {
        return $class->sub_teacher_id === $id;
    }

}
