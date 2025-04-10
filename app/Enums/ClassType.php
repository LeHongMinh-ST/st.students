<?php

declare(strict_types=1);

namespace App\Enums;

enum ClassType: string
{
    case Basic = 'basic';
    case Major = 'major';

    /**
     * Get all enum values with their labels.
     *
     * @return array<string, string>
     */
    public static function getLabels(): array
    {
        return [
            self::Basic->value => self::Basic->label(),
            self::Major->value => self::Major->label(),
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
            self::Basic => 'Lớp học cơ bản, dành cho các môn học chung.',
            self::Major => 'Lớp học chuyên ngành, dành cho các môn học chuyên sâu.',
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
            self::Basic => 'Cơ bản',
            self::Major => 'Chuyên ngành',
        };
    }
}
