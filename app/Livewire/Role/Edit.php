<?php

declare(strict_types=1);

namespace App\Livewire\Role;

use App\Models\GroupPermission;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

class Edit extends Component
{
    public Role $role;

    #[Validate(as: 'tên khoa')]
    public string $name;

    public array $permissionIds = [];

    public array $groupIds = [];

    private bool $isLoading = false;

    public function render()
    {
        $groupPermissions = GroupPermission::query()->with('permissions')->get();

        return view('livewire.role.edit', [
            'groupPermissions' => $groupPermissions
        ]);
    }

    public function mount(Role $role): void
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->permissionIds = $role->permissions->pluck('id')->toArray();
    }

    public function updatedPermissionIds($permissionId): void
    {
        dd($permissionId, $this->permissionIds);
        if (in_array($permissionId, $this->permissionIds)) {
            $this->permissionIds = array_diff($this->permissionIds, [$permissionId]);

            $groups = GroupPermission::whereHas('permissions', function ($query) use ($permissionId): void {
                $query->where('id', $permissionId);
            })->get();

            foreach ($groups as $group) {
                $groupPermissions = $group->permissions->pluck('id')->toArray();
                if (array_intersect($groupPermissions, $this->permissionIds) !== $groupPermissions) {
                    $this->groupIds = array_diff($this->groupIds, [$group->id]);
                }
            }
        } else {
            $this->permissionIds[] = $permissionId;

            $groups = GroupPermission::whereHas('permissions', function ($query) use ($permissionId): void {
                $query->where('id', $permissionId);
            })->get();

            foreach ($groups as $group) {
                $groupPermissions = $group->permissions->pluck('id')->toArray();
                if (empty(array_diff($groupPermissions, $this->permissionIds))) {
                    if (!in_array($group->id, $this->groupIds)) {
                        $this->groupIds[] = $group->id;
                    }
                }
            }
        }
    }

    public function toogleGroup(int $groupId): void
    {
        if (in_array($groupId, $this->groupIds)) {
            $this->groupIds = array_diff($this->groupIds, [$groupId]);
            $permissions = GroupPermission::find($groupId)->permissions;
            $this->permissionIds = array_diff($this->permissionIds, $permissions->pluck('id')->toArray());
        } else {
            $this->groupIds[] = $groupId;
            $permissions = GroupPermission::find($groupId)->permissions;
            $this->permissionIds = array_merge($this->permissionIds, $permissions->pluck('id')->toArray());
        }
    }

    public function submit(): void
    {
        if ($this->isLoading) {
            return;
        }
        try {
            $this->isLoading = true;
            $this->validate();

            $this->role->update([
                'name' => $this->name,
            ]);

            $this->role->permissions()->sync($this->permissions);

            $this->dispatch('alert', type: 'success', message: 'Cập nhật thất bại!');
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            $this->dispatch('alert', type: 'error', message: 'Cập nhật thất bại!');
        } finally {
            $this->isLoading = false;
        }
    }
}
