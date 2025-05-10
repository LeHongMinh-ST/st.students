<?php

declare(strict_types=1);

namespace App\Livewire\User;

use App\Enums\Role;
use App\Enums\UserType;
use App\Helpers\Constants;
use App\Models\User;
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

        $users = User::query()
            ->with('userRoles')
            ->where('role', '!=', Role::Student)
            ->where('faculty_id', $facultyId)
            ->where(function ($query): void {
                $query->whereNotNull('type')
                    ->orWhere('type', '!=', UserType::Student->value);
            })
            ->search($this->search)
            ->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE);

        return view('livewire.user.index', [
            'users' => $users,
        ]);
    }

    public function placeholder()
    {
        return view('components.placeholders.table-placeholder');
    }


}
