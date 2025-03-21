<?php

declare(strict_types=1);

namespace App\Enums;

enum PostStatus: string
{
    case PUBLISH = 'publish';
    case DRAFT = 'draft';
    case HIDE = 'hide';
}
