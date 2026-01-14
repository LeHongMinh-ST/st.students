<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exports\ImportErrorExport;
use App\Http\Controllers\Controller;
use App\Models\ImportHistory;
use App\Services\SsoService;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
     * Download error log as Excel file for a specific import history.
     * The exported file follows the same format as the import template,
     * allowing users to fix errors and re-import.
     *
     * @param int $historyId The import history ID
     * @return BinaryFileResponse
     */
    public function downloadImportErrorLog(int $historyId): BinaryFileResponse
    {
        $history = ImportHistory::with('errors')->findOrFail($historyId);

        // Check if this history belongs to current faculty
        $facultyId = app(SsoService::class)->getFacultyId();
        if ($history->faculty_id !== $facultyId) {
            abort(403);
        }

        $fileName = 'ban_ghi_loi_' . $history->id . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new ImportErrorExport($history), $fileName);
    }
}
