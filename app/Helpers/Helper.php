<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class Helper
{
    public static function splitFullName($fullName)
    {
        $parts = explode(' ', mb_trim($fullName));

        Log::info(json_encode($parts));

        if (1 === count($parts)) {
            return ['last_name' => '', 'first_name' => @$parts[0]];
        }

        $firstName = array_pop($parts);

        $lastName = implode(' ', $parts);

        return ['last_name' => $lastName, 'first_name' => $firstName];
    }
}
