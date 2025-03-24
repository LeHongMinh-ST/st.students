<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\LogActivity;
use App\Services\SsoService;
use Illuminate\Support\Facades\Auth;

class LogActivityHelper
{
    public static function create($action, $details = ''): void
    {
        $userData = app(SsoService::class)->getDataUser();

        LogActivity::create([
            'user_id' => Auth::id(),
            'user_name' => $userData['full_name'],
            'action' => $action,
            'details' => $details,
            'ip_address' => request()->ip(),
        ]);
    }
}
