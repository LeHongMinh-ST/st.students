<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportHistory;
use App\Services\SsoService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileContrller extends Controller
{
    public function downloadFileTemplateImport($name)
    {
        $name = basename($name);
        if (!file_exists(public_path('/templates/' . $name))) {
            abort(404);
        }
        return response()->download(public_path('/templates/' . $name));
    }

    /**
     * Download error log as CSV file for a specific import history.
     *
     * @param int $historyId The import history ID
     * @return StreamedResponse
     */
    public function downloadImportErrorLog(int $historyId): StreamedResponse
    {
        $history = ImportHistory::with('errors')->findOrFail($historyId);

        // Check if this history belongs to current faculty
        $facultyId = app(SsoService::class)->getFacultyId();
        if ($history->faculty_id !== $facultyId) {
            abort(403);
        }

        $errors = $history->errors;

        $fileName = 'error_log_' . $history->id . '_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($errors): void {
            $handle = fopen('php://output', 'w');

            // Add BOM for UTF-8 encoding (Excel compatibility)
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Write CSV header
            fputcsv($handle, [
                'STT',
                'Dòng',
                'Lỗi',
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
                'Địa chỉ',
                'Họ tên bố',
                'SĐT bố',
                'Họ tên mẹ',
                'SĐT mẹ',
            ]);

            // Write error records
            foreach ($errors as $index => $error) {
                $recordData = json_decode($error->record_data, true) ?? [];

                fputcsv($handle, [
                    $index + 1,
                    $error->row_number,
                    $error->error_message,
                    $recordData[1] ?? '',
                    $recordData[2] ?? '',
                    $recordData[3] ?? '',
                    $recordData[4] ?? '',
                    $recordData[5] ?? '',
                    $recordData[6] ?? '',
                    $recordData[7] ?? '',
                    $recordData[8] ?? '',
                    $recordData[9] ?? '',
                    $recordData[10] ?? '',
                    $recordData[11] ?? '',
                    $recordData[12] ?? '',
                    $recordData[13] ?? '',
                    $recordData[14] ?? '',
                    $recordData[15] ?? '',
                    $recordData[16] ?? '',
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
