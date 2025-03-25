<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusImport: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';
    case PartialyFaild = 'partially_failed';
}
