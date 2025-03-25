<?php

declare(strict_types=1);

namespace App\Imports;

use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentPreviewImport implements ToArray, WithStartRow, WithChunkReading
{
    use RemembersRowNumber;

    public array $data = [];


    public function array(array $array): void
    {

        $chunkData = array_map(fn ($row) => [
            'stt'             => $row[0] ?? '',
            'ma_nhap_hoc'     => $row[1] ?? '',
            'ma_sv'           => $row[2] ?? '',
            'ho_ten'          => $row[3] ?? '',
            'ngay_sinh'       => $row[4] ?? '',
            'gioi_tinh'       => $row[5] ?? '',
            'lop'            => $row[6] ?? '',
            'khoa'            => $row[7] ?? '',
            'nien_khoa'       => $row[8] ?? '',
            'dan_toc'        => $row[9] ?? '',
            'dien_thoai'      => $row[10] ?? '',
            'email'          => $row[11] ?? '',
            'dia_chi_bao_tin' => $row[12] ?? '',
            'ho_ten_bo'       => $row[13] ?? '',
            'sdt_bo'          => $row[14] ?? '',
            'ho_ten_me'      => $row[15] ?? '',
            'sdt_me'          => $row[16] ?? '',
        ], $array);

        // Merge the new chunk data with existing data
        $this->data = array_merge($this->data, $chunkData);
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
