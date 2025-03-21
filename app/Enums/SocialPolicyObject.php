<?php

declare(strict_types=1);

namespace App\Enums;

enum SocialPolicyObject: string
{
    case None = 'none';
    case SonOfWounded = 'son_of_wounded';
    case EspeciallyDifficult = 'especially_difficult';
    case EthnicMinorityPeopleInTheHighlands = 'ethnic_minority_people_in_the_highlands';
    case DisabledPerson = 'disabled_person';
}
