<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SsoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Throwable;

class AuthenticateController extends Controller
{
    public function redirectToSSO()
    {
        $query = http_build_query([
            'client_id' => config('auth.sso.client_id'),
            'redirect_uri' => route('sso.callback'),
            'response_type' => 'code',
            'scope' => '',
        ]);

        return redirect(config('auth.sso.uri') . '/oauth/authorize?' . $query);
    }

    public function handleCallback(Request $request)
    {
        try {
            $data = $this->getAccessToken($request->code);

            if (! isset($data['access_token'])) {
                return abort(401);
            }

            Session::put('access_token', $data['access_token']);

            $userData = $this->getUserData($data['access_token']);
            $user = $this->findOrCreateUser($userData);

            $this->storeSessionData($userData);
            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (Throwable $th) {
            Log::error($th->getMessage());

            return abort(401);
        }
    }

    public function logout()
    {
        app(SsoService::class)->clearAuth();

        return redirect(config('auth.sso.uri'));
    }

    private function getAccessToken(string $code): array
    {
        $response = Http::asForm()->post(config('auth.sso.uri') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('auth.sso.client_id'),
            'client_secret' => config('auth.sso.client_secret'),
            'redirect_uri' => route('sso.callback'),
            'code' => $code,
        ]);

        return $response->json();
    }

    private function getUserData(string $accessToken): array
    {
        $response = Http::withToken($accessToken)->get(config('auth.sso.uri') . '/api/user');

        return $response->json();
    }

    private function findOrCreateUser(array $userData): User
    {
        return User::firstOrCreate(
            ['sso_id' => $userData['id']],
            ['status' => Status::Active]
        );
    }

    private function storeSessionData(array $userData): void
    {
        Session::put('userData', $userData);

        if ($userData['role'] !== Role::SuperAdmin->value && empty($userData['faculty_id'])) {
            abort(403);
        }

        if ($userData['role'] !== Role::SuperAdmin->value) {
            Session::put('faculty_id', $userData['faculty_id']);
        }
    }
}
