<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class YearRange implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (! $this->checkValid($value)) {
            $fail('The :attribute must be in the format yyyy-yyyy with the first year less than or equal to the second year.');
        }
    }

    /**
     * Determine if the validation rule passes.
     */
    private function checkValid(mixed $value): bool
    {
        // Check if the value matches the pattern xxxx-xxxx with x being a digit
        if (preg_match('/^\d{4}-\d{4}$/', $value)) {
            // Split the value into two parts
            [$startYear, $endYear] = explode('-', $value);

            // Convert to integers and check if start year is less than or equal to end year
            return (int) $startYear <= (int) $endYear;
        }

        return false;
    }
}
