<?php

declare(strict_types=1);

namespace App\Enums;

enum StudentInfoUpdateStatus: string
{
    case Pending = 'pending';
    case ClassOfficerApproved = 'class_officer_approved';
    case TeacherApproved = 'teacher_approved';
    case OfficerApproved = 'officer_approved';
    case Reject = 'reject';
}
