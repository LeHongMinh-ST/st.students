<?php

declare(strict_types=1);

namespace App\Helpers;

class Helper
{
    public static function splitFullName($fullName)
    {
        $parts = explode(' ', mb_trim($fullName));

        if (1 === count($parts)) {
            return ['last_name' => $parts[0], 'first_name' => ''];
        }

        $lastName = array_shift($parts);
        $firstName = implode(' ', $parts);

        return ['last_name' => $lastName, 'first_name' => $firstName];
    }
}
