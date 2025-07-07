<?php

declare(strict_types=1);

namespace App\Providers;

use App\View\Components\Import\StatusBadge as ImportStatusBadge;
use App\View\Components\Layouts\AdminLayout;
use App\View\Components\Layouts\AuthLayout;
use App\View\Components\Layouts\ClientLayout;
use App\View\Components\Placeholders\TablePlaceholder;
use App\View\Components\Student\StatusBadge as StudentStatusBadge;
use App\View\Components\StudentRoleBadge;
use App\View\Components\Table\TableEmpty;
use App\View\Components\User\RoleBadge;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        if (App::environment('production')) {
            URL::forceScheme('https');
        }

        LogViewer::auth(fn () => 'super_admin' === auth()->user()->role);


        Blade::component('auth-layout', AuthLayout::class);
        Blade::component('admin-layout', AdminLayout::class);
        Blade::component('client-layout', ClientLayout::class);
        Blade::component('table-empty', TableEmpty::class);
        Blade::component('role-badge', RoleBadge::class);
        Blade::component('import-status-badge', ImportStatusBadge::class);
        Blade::component('table-placeholder', TablePlaceholder::class);
        Blade::component('student-status-badge', StudentStatusBadge::class);
        Blade::component('student-role-badge', StudentRoleBadge::class);
    }
}
