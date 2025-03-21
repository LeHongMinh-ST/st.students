<?php

declare(strict_types=1);

namespace App\Enums;

enum ReflectStatus: string
{
    case Pending = 'pending';
    case Seend = 'seend';
    case Approved = 'approved';
    case Reject = 'reject';
}
