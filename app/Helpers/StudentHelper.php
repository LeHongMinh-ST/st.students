<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Student;
use App\Models\User;

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
        // Superadmin có thể xem tất cả sinh viên
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Sinh viên chỉ có thể xem thông tin của chính mình
        if ($user->isStudent()) {
            return $student->user_id === $user->id;
        }

        // Giáo viên chủ nhiệm có thể xem sinh viên trong lớp mình chủ nhiệm
        if ($user->hasPermission('class.teacher')) {
            // Lấy danh sách lớp mà giáo viên là chủ nhiệm
            $teacherClassIds = \Illuminate\Support\Facades\DB::table('class_assigns')
                ->where('teacher_id', $user->id)
                ->where('status', \App\Enums\Status::Active->value)
                ->pluck('class_id')
                ->toArray();

            // Kiểm tra xem sinh viên có thuộc lớp nào mà giáo viên là chủ nhiệm không
            $studentClassIds = \Illuminate\Support\Facades\DB::table('class_students')
                ->where('student_id', $student->id)
                ->pluck('class_id')
                ->toArray();

            // Nếu có ít nhất một lớp chung, cho phép xem
            if (count(array_intersect($teacherClassIds, $studentClassIds)) > 0) {
                return true;
            }
        }

        // Cố vấn học tập có thể xem sinh viên trong lớp mình cố vấn
        if ($user->hasPermission('class.sub_teacher')) {
            // Lấy danh sách lớp mà giáo viên là cố vấn
            $subTeacherClassIds = \Illuminate\Support\Facades\DB::table('class_assigns')
                ->where('sub_teacher_id', $user->id)
                ->where('status', \App\Enums\Status::Active->value)
                ->pluck('class_id')
                ->toArray();

            // Kiểm tra xem sinh viên có thuộc lớp nào mà giáo viên là cố vấn không
            $studentClassIds = \Illuminate\Support\Facades\DB::table('class_students')
                ->where('student_id', $student->id)
                ->pluck('class_id')
                ->toArray();

            // Nếu có ít nhất một lớp chung, cho phép xem
            if (count(array_intersect($subTeacherClassIds, $studentClassIds)) > 0) {
                return true;
            }
        }

        return false;
    }

    // Các phương thức isTeacher và isSubTeacher đã được thay thế bằng cách kiểm tra trực tiếp trong checkUserStudent

}
