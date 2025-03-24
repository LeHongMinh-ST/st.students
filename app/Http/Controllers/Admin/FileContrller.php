<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

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
}
