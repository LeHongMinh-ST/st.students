<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index(): View|Application|Factory|RedirectResponse
    {
        if (auth()->user()->isStudent()) {
            return redirect()->route('student.profile');
        }

        // Kiểm tra xem người dùng có ít nhất một quyền xem thống kê nào đó không
        $user = auth()->user();
        $hasAnyDashboardPermission = $user->isAdmin() ||
            $user->hasPermission('dashboard.students') ||
            $user->hasPermission('dashboard.graduated') ||
            $user->hasPermission('dashboard.warned') ||
            $user->hasPermission('dashboard.classes');

        if (!$hasAnyDashboardPermission) {
            abort(403, 'Bạn không có quyền xem bảng điều khiển.');
        }

        return view('pages.dashboard');
    }
}
