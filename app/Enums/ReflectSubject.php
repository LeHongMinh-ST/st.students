<?php

declare(strict_types=1);

namespace App\Enums;

enum ReflectSubject: string
{
    case Study = 'study';
    case Diligence = 'diligence';
    case SchoolUnionActivities = 'school_union_activities';
    case Reward = 'reward';
    case Life = 'life';
    case Other = 'other';
}
