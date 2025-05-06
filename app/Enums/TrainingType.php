<?php

declare(strict_types=1);

namespace App\Enums;

enum TrainingType: string
{
    case FormalUniversity = 'formal_university';
    case College = 'college';

    /**
     * Get all enum values with their labels.
     *
     * @return array<string, string>
     */
    public static function getLabels(): array
    {
        return [
            self::FormalUniversity->value => self::FormalUniversity->label(),
            self::College->value => self::College->label(),
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
            self::FormalUniversity => 'Đào tạo chính quy đại học.',
            self::College => 'Đào tạo cao đẳng.',
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
            self::FormalUniversity => 'Đại học chính quy',
            self::College => 'Cao đẳng',
        };
    }
}
