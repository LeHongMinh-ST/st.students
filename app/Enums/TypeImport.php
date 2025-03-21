<?php

declare(strict_types=1);

namespace App\Enums;

enum TypeImport: string
{
    case Student = 'student';
    case StudentWarning = 'student_warning';
    case StudentQuit = 'student_quit';
    case StudentGraduate = 'student_graduate';
}
