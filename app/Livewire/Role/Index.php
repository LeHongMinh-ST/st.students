<?php

declare(strict_types=1);

namespace App\Livewire\Role;

use App\Helpers\Constants;
use App\Models\Role;
use App\Services\SsoService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public function render()
    {
        $facultyId = app(SsoService::class)->getFacultyId();

        $roles = Role::query()
            ->search($this->search)
            ->where('faculty_id', $facultyId)
            ->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE);

        return view('livewire.role.index', [
            'roles' => $roles
        ]);
    }
}
