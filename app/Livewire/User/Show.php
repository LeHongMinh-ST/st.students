<?php

declare(strict_types=1);

namespace App\Livewire\User;

use App\Helpers\Constants;
use App\Helpers\LogActivityHelper;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Show extends Component
{
    public User $user;

    public array $userData = [];

    public string $tab = 'profile';

    public string $search = '';

    public array $roleIds = [];

    public bool $selectAll = false;

    public function render()
    {

        $roles = Role::query()
            ->search($this->search)
            ->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE, );

        return view('livewire.user.show', [
            'roles' => $roles
        ]);
    }

    public function mount($user, $userData): void
    {
        $this->user = $user;
        $this->userData = $userData;
        $this->roleIds = $user->userRoles()->pluck('roles.id')->toArray();
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function updatedRoleIds(): void
    {
        DB::transaction(function (): void {
            $this->user->userRoles()->sync($this->roleIds);
            LogActivityHelper::create("Phân quyền", "gán quyền {$this->user->roleName} cho người dùng {$this->userData['full_name']}");
        });
    }

    public function updatedSelectAll(): void
    {
        if ($this->selectAll) {
            $this->roleIds = Role::all()->pluck('id')->toArray();

        } else {
            $this->roleIds = [];
        }
    }
}
