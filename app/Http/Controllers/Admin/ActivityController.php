<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class ActivityController extends Controller
{
    public function index(): View|Application|Factory|RedirectResponse
    {
        // Kiểm tra quyền trực tiếp thay vì sử dụng policy
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('activity.index')) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return view('pages.activity.index');
    }
}
