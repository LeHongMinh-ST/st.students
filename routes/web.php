<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('sso-login', function () {
    $redirectUri = config('auth.sso.client_id');
    $ssoLoginUri = config('auth.sso.login_uri');

    return redirect("$ssoLoginUri?client_id=$redirectUri");
})->name('sso.login');

Route::get('/auth/redirect', function () {
    $query = http_build_query([
        'client_id' => config('auth.sso.client_id'),
        'redirect_uri' => route('sso.callback'),
        'response_type' => 'code',
        'scope' => '',
    ]);

    return redirect(config('auth.sso.uri').'/oauth/authorize?'.$query);
})->name('sso.redirect');

Route::get('/auth/callback', function (Request $request) {
    $response = Http::asForm()->post(env('SSO_SERVER_URL').'/oauth/token', [
        'grant_type' => 'authorization_code',
        'client_id' => config('auth.sso.client_id'),
        'client_secret' => config('auth.sso.client_secret'),
        'redirect_uri' => route('sso.callback'),
        'code' => $request->code,
    ]);

    $data = $response->json();

    if (! isset($data['access_token'])) {
        return redirect('/login')->withErrors(['error' => 'Lỗi xác thực']);
    }

    Session::put('access_token', $data['access_token']);
    dd($data);

    return redirect('/dashboard');
})->name('sso.callback');

Route::middleware('auth.sso')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});
