<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\ImportHistory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Export import errors to Excel file with the same format as import template.
 * This allows users to fix errors and re-import the file.
 */
class ImportErrorExport implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles
{
    protected ImportHistory $history;

    public function __construct(ImportHistory $history)
    {
        $this->history = $history;
    }

    /**
     * Define the headings for the Excel file.
     * Matches the import template format with additional error columns.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'STT',
            'Mã nhập học',
            'Mã SV',
            'Họ tên',
            'Ngày sinh',
            'Giới tính',
            'Lớp',
            'Khoa',
            'Niên khóa',
            'Dân tộc',
            'Điện thoại',
            'Email',
            'Địa chỉ báo tin',
            'Họ tên bố',
            'SĐT của bố',
            'Họ tên mẹ',
            'SĐT của mẹ',
            'Dòng lỗi',
            'Nội dung lỗi',
        ];
    }

    /**
     * Get the collection of error records to export.
     *
     * @return Collection
     */
    public function collection(): Collection
    {
        $errors = $this->history->errors()->orderBy('row_number')->get();

        return $errors->map(function ($error, $index) {
            $recordData = json_decode($error->record_data, true) ?? [];

            return [
                'stt' => $index + 1,
                'ma_nhap_hoc' => $recordData[1] ?? '',
                'ma_sv' => $recordData[2] ?? '',
                'ho_ten' => $recordData[3] ?? '',
                'ngay_sinh' => $recordData[4] ?? '',
                'gioi_tinh' => $recordData[5] ?? '',
                'lop' => $recordData[6] ?? '',
                'khoa' => $recordData[7] ?? '',
                'nien_khoa' => $recordData[8] ?? '',
                'dan_toc' => $recordData[9] ?? '',
                'dien_thoai' => $recordData[10] ?? '',
                'email' => $recordData[11] ?? '',
                'dia_chi' => $recordData[12] ?? '',
                'ho_ten_bo' => $recordData[13] ?? '',
                'sdt_bo' => $recordData[14] ?? '',
                'ho_ten_me' => $recordData[15] ?? '',
                'sdt_me' => $recordData[16] ?? '',
                'dong_loi' => $error->row_number,
                'noi_dung_loi' => $error->error_message,
            ];
        });
    }

    /**
     * Apply styles to the Excel worksheet.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        $lastRow = $this->history->errors()->count() + 1;

        return [
            // Style the header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ],
            // Style the error columns (R and S) with red background
            "R2:S{$lastRow}" => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFCCCC'],
                ],
                'font' => [
                    'color' => ['rgb' => 'CC0000'],
                ],
            ],
        ];
    }
}
