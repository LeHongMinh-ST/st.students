<?php

declare(strict_types=1);

namespace App\Providers;

use App\View\Components\Layouts\AdminLayout;
use App\View\Components\Layouts\AuthLayout;
use App\View\Components\Layouts\ClientLayout;
use App\View\Components\Table\TableEmpty;
use App\View\Components\User\RoleBadge;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event): void {
            $event->extendSocialite('azure', \SocialiteProviders\Azure\Provider::class);
        });

        Passport::loadKeysFrom(__DIR__ . '/../secrets/oauth');
        Passport::hashClientSecrets();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addDays(15));

        Blade::component('auth-layout', AuthLayout::class);
        Blade::component('admin-layout', AdminLayout::class);
        Blade::component('client-layout', ClientLayout::class);
        Blade::component('table-empty', TableEmpty::class);
        Blade::component('role-badge', RoleBadge::class);
    }
}
