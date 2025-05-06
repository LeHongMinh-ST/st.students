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

    /**
     * Get all enum values with their labels.
     *
     * @return array<string, string>
     */
    public static function getLabels(): array
    {
        return [
            self::None->value => self::None->label(),
            self::SonOfWounded->value => self::SonOfWounded->label(),
            self::EspeciallyDifficult->value => self::EspeciallyDifficult->label(),
            self::EthnicMinorityPeopleInTheHighlands->value => self::EthnicMinorityPeopleInTheHighlands->label(),
            self::DisabledPerson->value => self::DisabledPerson->label(),
        ];
    }

    /**
     * Get the description of the enum value.
     *
     * @return string
     */
    public function description(): string
    {
        return match($this) {
            self::None => 'Không thuộc đối tượng chính sách nào.',
            self::SonOfWounded => 'Con thương binh, liệt sĩ.',
            self::EspeciallyDifficult => 'Đối tượng đặc biệt khó khăn.',
            self::EthnicMinorityPeopleInTheHighlands => 'Dân tộc thiểu số ở vùng cao.',
            self::DisabledPerson => 'Người khuyết tật.',
        };
    }

    /**
     * Get the label of the enum value.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::None => 'Không',
            self::SonOfWounded => 'Con thương binh, liệt sĩ',
            self::EspeciallyDifficult => 'Đối tượng đặc biệt khó khăn',
            self::EthnicMinorityPeopleInTheHighlands => 'Dân tộc thiểu số ở vùng cao',
            self::DisabledPerson => 'Người khuyết tật',
        };
    }
}
