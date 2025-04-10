<?php

declare(strict_types=1);

namespace App\Enums;

enum FamilyRelationship: string
{
    case Father = 'father';
    case Mother = 'mother';
    case Siblings = 'siblings';

    /**
     * Get all enum values with their labels.
     *
     * @return array<string, string>
     */
    public static function getLabels(): array
    {
        return [
            self::Father->value => self::Father->label(),
            self::Mother->value => self::Mother->label(),
            self::Siblings->value => self::Siblings->label(),
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
            self::Father => 'Bố hoặc người giám hộ nam.',
            self::Mother => 'Mẹ hoặc người giám hộ nữ.',
            self::Siblings => 'Anh, chị, em ruột hoặc cùng gia đình.',
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
            self::Father => 'Bố',
            self::Mother => 'Mẹ',
            self::Siblings => 'Anh/Chị/Em',
        };
    }

    /**
     * Get the badge color for the relationship.
     *
     * @return string
     */
    public function badgeColor(): string
    {
        return match($this) {
            self::Father => 'bg-primary',
            self::Mother => 'bg-info',
            self::Siblings => 'bg-secondary',
        };
    }
}
