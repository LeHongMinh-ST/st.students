<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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

        return redirect(config('auth.sso.uri').'/oauth/authorize?'.$query);
    }

    public function handleCallback(Request $request)
    {
        $response = Http::asForm()->post(config('auth.sso.uri').'/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('auth.sso.client_id'),
            'client_secret' => config('auth.sso.client_secret'),
            'redirect_uri' => route('sso.callback'),
            'code' => $request->code,
        ]);

        $data = $response->json();

        if (! isset($data['access_token'])) {
            return abort(401);
        }

        Session::put('access_token', $data['access_token']);

        try {
            // Get user information using access token
            $userResponse = Http::withToken($data['access_token'])->get(config('auth.sso.uri').'/api/user');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return abort(401);
        }

        $userData = $userResponse->json();

        $user = User::where('sso_id', $userData['id'])->first();
        if (! $user) {
            $user = User::create([
                'sso_id' => $userData['id'],
                'status' => Status::Active,
            ]);
        }

        Session::put('userData', $userData);

        if ($userData['role'] !== Role::SuperAdmin->value && empty($userData['faculty_id'])) {
            return abort(403);
        }

        if ($userData['role'] !== Role::SuperAdmin->value) {
            Session::put('faculty_id', $userData['faculty_id']);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        Auth::logout();
        Session::forget('access_token');
        Session::forget('userData');

        return redirect(config('auth.sso.uri'));
    }
}
