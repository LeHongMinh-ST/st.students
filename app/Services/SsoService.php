<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Throwable;

class SsoService
{
    private $accessToken;

    public function __construct()
    {
        $this->accessToken = Session::get('access_token');
    }

    public function get(string $endPoint, $data = [])
    {
        try {
            $response = Http::withToken($this->accessToken)->get(config('auth.sso.uri').$endPoint, $data);

            return $response->json();
        } catch (Throwable $th) {
            Log::error($th->getMessage());

            if ($th->getCode() === 401) {
                $this->clearAuth();
                abort(401);
            }

            return [];
        }
    }

    public function post(string $endPoint, $data = [])
    {
        try {
            $response = Http::withToken($this->accessToken)->post(config('auth.sso.uri').$endPoint, $data);

            return $response->json();
        } catch (Throwable $th) {
            Log::error($th->getMessage());

            if ($th->getCode() === 401) {
                $this->clearAuth();
                abort(401);
            }

            return [];
        }
    }

    public function clearAuth()
    {
        Auth::logout();
        Session::forget('access_token');
        Session::forget('userData');
        Session::forget('facultyId');
    }
}
