<?php

declare(strict_types=1);

namespace App\Enums;

enum FamilyRelationship: string
{
    case Father = 'father';
    case Mother = 'mother';
    case Siblings = 'siblings';
}
