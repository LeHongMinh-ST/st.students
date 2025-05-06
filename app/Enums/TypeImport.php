<?php

declare(strict_types=1);

namespace App\Enums;

enum TypeImport: string
{
    case Student = 'student';
    case StudentWarning = 'student_warning';
    case StudentQuit = 'student_quit';
    case StudentGraduate = 'student_graduate';
    case TeacherAssignment = 'teacher_assignment';

    /**
     * Get the label of the enum value.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::Student => 'Sinh viên',
            self::StudentWarning => 'Sinh viên cảnh báo',
            self::StudentQuit => 'Sinh viên nghỉ học',
            self::StudentGraduate => 'Sinh viên tốt nghiệp',
            self::TeacherAssignment => 'Phân công giáo viên chủ nhiệm',
        };
    }
}
