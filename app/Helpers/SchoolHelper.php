<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Rules\YearRange;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class SchoolHelper
{
    // Define the constants
    private const START_SCHOOL_YEAR = 1956;

    private const START_ADMISSION_YEAR = 1;

    /**
     * Calculate the class based on the given year.
     */
    public static function calculateAdmissionYear(int $year): int
    {
        // Calculate the number of years from the start year
        $yearsDifference = $year - self::START_SCHOOL_YEAR;

        // Calculate the class number
        return self::START_ADMISSION_YEAR + $yearsDifference;
    }

    /**
     * Get the start and end year from a school year string.
     *
     * This function takes a school year string in the format "YYYY-YYYY",
     * validates it, and returns the start and end year as an array.
     *
     * @param  string  $schoolYear  The school year string to be processed.
     * @return array<int, int> An array containing the start year and end year.
     *
     * @throws InvalidArgumentException If the school year format is invalid.
     */
    public static function getStartEndYearInSchoolYear(string $schoolYear): array
    {
        // Validate the school year format using a custom YearRange rule
        $validator = Validator::make([
            'school_year' => $schoolYear,
        ], [
            'school_year' => [new YearRange()],
        ]);

        // If validation fails, throw an exception
        if ($validator->fails()) {
            throw new InvalidArgumentException('Invalid school year');
        }

        // Split the school year string into start and end years
        [$startYear, $endYear] = explode('-', $schoolYear);

        // Return the start and end year as an array
        return [(int) $startYear, (int) $endYear];
    }
}
