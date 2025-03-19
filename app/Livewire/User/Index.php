<?php

namespace App\Livewire\User;

use App\Services\SsoService;
use Illuminate\Support\Facades\Session;
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
        $facultyId = Session::get('facultyId');

        $params = [
            'page' => $this->page,
        ];

        if ($this->search) {
            $params['search'] = $this->search;
        }

        $responses = app(SsoService::class)->get("/api/faculties/$facultyId/users", $params);

        $this->page = $responses['current_page'] ?? 1;
        $this->totalPages = $responses['last_page'] ?? 1;

        return $responses['data'];
    }

    #[On('onPageChange')]
    public function onUpdatePage($page)
    {
        $this->page = (int) $page;
    }
}
