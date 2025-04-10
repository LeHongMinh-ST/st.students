<?php

declare(strict_types=1);

namespace App\Livewire\Graduation;

use App\Models\GraduationCeremony;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public GraduationCeremony $ceremony;

    #[Validate(as: 'tên đợt tốt nghiệp')]
    public string $name = '';

    #[Validate(as: 'năm học')]
    public string $school_year = '';

    #[Validate(as: 'số quyết định')]
    public string $certification = '';

    #[Validate(as: 'ngày quyết định')]
    public string $certification_date = '';

    public function mount(GraduationCeremony $ceremony): void
    {
        $this->ceremony = $ceremony;
        $this->name = $ceremony->name;
        $this->school_year = $ceremony->school_year;
        $this->certification = $ceremony->certification;
        $this->certification_date = $ceremony->certification_date;
    }

    public function render()
    {
        return view('livewire.graduation.edit');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'school_year' => 'required|string|max:50',
            'certification' => 'required|string|max:50',
            'certification_date' => 'required|date',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->ceremony->update([
            'name' => $this->name,
            'school_year' => $this->school_year,
            'certification' => $this->certification,
            'certification_date' => $this->certification_date,
        ]);

        session()->flash('success', 'Đợt tốt nghiệp đã được cập nhật thành công.');
        $this->redirect(route('graduation.index'));
    }

    public function delete(): void
    {
        $this->ceremony->delete();
        session()->flash('success', 'Đợt tốt nghiệp đã được xóa thành công.');
        $this->redirect(route('graduation.index'));
    }
}
