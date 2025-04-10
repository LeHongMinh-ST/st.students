<?php

declare(strict_types=1);

namespace App\Enums;

enum RankGraduate: string
{
    case Average = 'average';
    case Good = 'good';
    case VeryGood = 'very_good';
    case Excellent = 'excellent';

    /**
     * Get all enum values with their labels.
     *
     * @return array<string, string>
     */
    public static function getLabels(): array
    {
        return [
            self::Average->value => self::Average->label(),
            self::Good->value => self::Good->label(),
            self::VeryGood->value => self::VeryGood->label(),
            self::Excellent->value => self::Excellent->label(),
        ];
    }

    /**
     * Get the rank based on GPA.
     *
     * @param float $gpa
     * @return self
     */
    public static function fromGpa(float $gpa): self
    {
        return match(true) {
            $gpa >= 9.0 => self::Excellent,
            $gpa >= 8.0 => self::VeryGood,
            $gpa >= 6.5 => self::Good,
            default => self::Average,
        };
    }

    /**
     * Get the description of the enum value.
     *
     * @return string
     */
    public function description(): string
    {
        return match($this) {
            self::Average => 'Xếp loại trung bình, điểm trung bình từ 5.0 đến dưới 6.5.',
            self::Good => 'Xếp loại khá, điểm trung bình từ 6.5 đến dưới 8.0.',
            self::VeryGood => 'Xếp loại giỏi, điểm trung bình từ 8.0 đến dưới 9.0.',
            self::Excellent => 'Xếp loại xuất sắc, điểm trung bình từ 9.0 trở lên.',
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
            self::Average => 'Trung bình',
            self::Good => 'Khá',
            self::VeryGood => 'Giỏi',
            self::Excellent => 'Xuất sắc',
        };
    }

    /**
     * Get the badge color for the rank.
     *
     * @return string
     */
    public function badgeColor(): string
    {
        return match($this) {
            self::Average => 'bg-secondary',
            self::Good => 'bg-info',
            self::VeryGood => 'bg-primary',
            self::Excellent => 'bg-success',
        };
    }
}
