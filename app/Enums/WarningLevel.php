<?php

declare(strict_types=1);

namespace App\Enums;

enum WarningLevel: string
{
    case Level1 = 'level_1';
    case Level2 = 'level_2';

    /**
     * Get all enum values with their labels.
     *
     * @return array<string, string>
     */
    public static function getLabels(): array
    {
        return [
            self::Level1->value => self::Level1->label(),
            self::Level2->value => self::Level2->label(),
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
            self::Level1 => 'Cảnh báo mức 1: Sinh viên có trong đợt cảnh báo gần nhất.',
            self::Level2 => 'Cảnh báo mức 2: Sinh viên có trong 2 đợt cảnh báo liên tiếp gần nhất.',
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
            self::Level1 => 'Cảnh báo mức 1',
            self::Level2 => 'Cảnh báo mức 2',
        };
    }

    /**
     * Get the badge color for the warning level.
     *
     * @return string
     */
    public function badgeColor(): string
    {
        return match($this) {
            self::Level1 => 'bg-warning',
            self::Level2 => 'bg-danger',
        };
    }
}
