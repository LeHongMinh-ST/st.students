<?php

declare(strict_types=1);

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GraduationStudentPreviewImport implements ToArray, WithHeadingRow
{
    public function array(array $rows): array
    {
        return $rows;
    }
}
