<?php

declare(strict_types=1);

namespace App\Livewire\Role;

use App\Helpers\LogActivityHelper;
use App\Models\Role;
use App\Services\SsoService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    #[Validate(as: 'tên khoa')]
    public $name;

    #[Validate(as: 'mô tả')]
    public $description;

    private bool $isLoading = false;

    public function render()
    {
        return view('livewire.role.create');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
        ];
    }

    public function submit()
    {
        Gate::authorize('create', Role::class);

        if ($this->isLoading) {
            return;
        }
        try {
            $this->isLoading = true;
            $this->validate();

            $facultyId = app(SsoService::class)->getFacultyId();

            $role = Role::create([
                'name' => $this->name,
                'description' => $this->description,
                'faculty_id' => $facultyId
            ]);

            LogActivityHelper::create("Tạo vai trò", "tạo vai trò {$role->name}");

            session()->flash('success', 'Tạo mới thành công!');
            return redirect()->route('roles.edit', $role->id);
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            $this->dispatch('alert', type: 'error', message: 'Tạo mới thất bại!');
        } finally {
            $this->isLoading = false;
        }
    }
}
