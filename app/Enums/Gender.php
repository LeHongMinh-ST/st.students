<?php

declare(strict_types=1);

namespace App\Enums;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';

    public static function mapValue(string $gender): self
    {
        return match (mb_strtolower($gender)) {
            'nam', 'male' => self::Male,
            'nữ', 'nu', 'female' => self::Female,
            default => self::Male,
        };
    }
}
