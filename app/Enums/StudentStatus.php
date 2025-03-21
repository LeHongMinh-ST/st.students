<?php

declare(strict_types=1);

namespace App\Enums;

enum StudentStatus: string
{
    case CurrentlyStudying = 'currently_studying';
    case Graduated = 'graduated';
    case TemporarilySuspended = 'temporarily_suspended';
    case Expelled = 'expelled';
    case Defer = 'defer';
    case TransferStudy = 'transfer_study';
}
