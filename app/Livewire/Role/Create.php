<?php

declare(strict_types=1);

namespace App\Livewire\Role;

use App\Models\Role;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    #[Validate(as: 'tên khoa')]
    public $name;

    private bool $isLoading = false;

    public function render()
    {
        return view('livewire.role.create');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255|unique:roles,name',
        ];
    }

    public function submit()
    {
        if ($this->isLoading) {
            return;
        }
        try {
            $this->isLoading = true;
            $this->validate();

            $role = Role::create([
                'name' => $this->name,
            ]);

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
