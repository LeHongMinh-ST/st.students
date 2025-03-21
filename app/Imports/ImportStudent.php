<?php

declare(strict_types=1);

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportStudent implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection): void
    {

    }
}
