<?php

declare(strict_types=1);

namespace App\Livewire\User;

use App\Models\User;
use App\Services\SsoService;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    #[Url]
    public int $page = 1;

    public int $totalPages = 0;

    #[Url(as: 'q')]
    public ?string $search = '';

    public function render()
    {
        $users = $this->fetchData();

        return view('livewire.user.index', [
            'users' => $users,
        ]);
    }

    public function fetchData()
    {
        $facultyId = app(SsoService::class)->getFacultyId();

        $params = [
            'page' => $this->page,
        ];

        if ($this->search) {
            $params['search'] = $this->search;
        }

        $responses = app(SsoService::class)->get("/api/faculties/{$facultyId}/users", $params);

        $this->page = @$responses['current_page'] ?? 1;
        $this->totalPages = @$responses['last_page'] ?? 1;
        $usersFromApi = @$responses['data'] ?? [];

        $ssoIds = collect($usersFromApi)->pluck('id')->toArray();

        $localUsers = User::whereIn('sso_id', $ssoIds)->get()->keyBy('sso_id');

        $users = collect($usersFromApi)->map(function ($user) use ($localUsers) {
            $localUser = $localUsers[$user['id']] ?? null;
            $user['local_user'] = $localUser ? $localUser->toArray() : null;
            return $user;
        })->toArray();

        return $users;
    }

    #[On('onPageChange')]
    public function onUpdatePage($page): void
    {
        $this->page = (int) $page;
    }
}
