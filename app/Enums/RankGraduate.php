<?php

declare(strict_types=1);

namespace App\Enums;

enum RankGraduate: string
{
    case Average = 'average';
    case Good = 'good';
    case VeryGood = 'very_good';
    case Excellent = 'excellent';
}
