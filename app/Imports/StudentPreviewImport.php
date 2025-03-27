<?php

declare(strict_types=1);

namespace App\Imports;

use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentPreviewImport implements ToArray, WithStartRow, WithChunkReading, WithHeadingRow
{
    use RemembersRowNumber;

    public array $data = [];


    public function array(array $array): void
    {
        $this->data = array_merge($this->data, $array);
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
