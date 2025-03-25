<?php

declare(strict_types=1);

namespace App\Livewire\Role;

use App\Helpers\LogActivityHelper;
use App\Models\GroupPermission;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

class Edit extends Component
{
    public Role $role;

    #[Validate(as: 'tên khoa')]
    public string $name;

    #[Validate(as: 'mô tả')]
    public $description;

    public array $permissionIds = [];

    public array $groupIds = [];

    public bool $selectAll = false;

    public array $groupIndeterminateStates = [];

    private bool $isLoading = false;

    public function render()
    {
        $groupPermissions = GroupPermission::query()->with('permissions')->get();

        return view('livewire.role.edit', [
            'groupPermissions' => $groupPermissions
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
        ];
    }

    public function mount(Role $role): void
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->description = $role->description;
        $this->permissionIds = $role->permissions->pluck('id')->toArray();
        $this->syncGroupIds();
        $this->updateGroupIndeterminateStates();

    }


    public function updatedGroupIds(): void
    {
        $this->syncPermissions();
        $this->updateGroupIndeterminateStates();
    }

    public function updatedPermissionIds(): void
    {
        $this->syncGroupIds();
        $this->updateGroupIndeterminateStates();
    }

    public function updatedSelectAll(): void
    {
        if ($this->selectAll) {
            $this->groupIds = GroupPermission::pluck('id')->toArray();
            $this->permissionIds = GroupPermission::with('permissions')
                ->get()
                ->pluck('permissions.*.id')
                ->flatten()
                ->toArray();
        } else {
            $this->groupIds = [];
            $this->permissionIds = [];
        }
    }

    public function submit(): void
    {
        Gate::authorize('update', $this->role);

        $this->validate();

        if ($this->isLoading) {
            return;
        }

        try {
            $this->isLoading = true;

            $this->role->update([
                'name' => $this->name,
                'description' => $this->description
            ]);

            $this->role->permissions()->sync($this->permissionIds);

            LogActivityHelper::create("Cập nhật vai trò", "cập nhật vai trò {$this->role->name}");

            $this->dispatch('alert', type: 'success', message: 'Cập nhật thành công');

        } catch (Throwable $th) {
            Log::error($th->getMessage());
            $this->dispatch('alert', type: 'error', message: 'Cập nhật thất bại!');
        } finally {
            $this->isLoading = false;
        }
    }

    #[On('deleteRole')]
    public function delete()
    {
        $this->role->delete();
        session()->flash('success', 'Xoá thành công!');
        return redirect()->route('roles.index');
    }

    public function openDeleteModal(): void
    {
        $this->dispatch('onOpenDeleteModal');
    }

    private function syncPermissions(): void
    {
        // Get all permissions from selected groups
        $selectedAndIndeterminateGroups = GroupPermission::with('permissions')
            ->whereIn('id', [...$this->groupIds, ...$this->groupIndeterminateStates])
            ->get();

        $selectGroupPermissionIds = $selectedAndIndeterminateGroups->pluck('permissions.*.id')->flatten()->toArray();


        $oldPermissions = array_intersect($this->permissionIds, $selectGroupPermissionIds);

        $selectGroup = GroupPermission::with('permissions')
            ->whereIn('id', $this->groupIds)
            ->get()
            ->pluck('permissions.*.id')
            ->flatten()
            ->toArray();

        $this->permissionIds = array_unique(array_merge($oldPermissions, $selectGroup));
    }

    private function syncGroupIds(): void
    {

        $this->groupIds = GroupPermission::whereHas('permissions', function ($query): void {
            $query->whereIn('id', $this->permissionIds);
        })->get()->filter(function ($group) {
            $groupPermissionIds = $group->permissions->pluck('id')->toArray();
            $selectedPermissionsCount = count(array_intersect($groupPermissionIds, $this->permissionIds));
            return count($groupPermissionIds) === $selectedPermissionsCount;
        })->pluck('id')->toArray();
    }

    private function updateGroupIndeterminateStates(): void
    {
        $groupIndeterminateStates = [];
        foreach (GroupPermission::with('permissions')->get() as $group) {
            $groupPermissionIds = $group->permissions->pluck('id')->toArray();
            $selectedPermissionsCount = count(array_intersect($groupPermissionIds, $this->permissionIds));

            if ($selectedPermissionsCount > 0 && $selectedPermissionsCount < count($groupPermissionIds)) {
                $groupIndeterminateStates[] = $group->id;
            }

            $this->dispatch(
                "setGroupIndeterminate",
                groupId: $group->id,
                indeterminate: $selectedPermissionsCount > 0 && $selectedPermissionsCount < count($groupPermissionIds)
            );
        }

        $this->groupIndeterminateStates = $groupIndeterminateStates;
    }

}
