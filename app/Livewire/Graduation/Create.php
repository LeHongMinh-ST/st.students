<?php

declare(strict_types=1);

namespace App\Livewire\Graduation;

use App\Models\GraduationCeremony;
use App\Services\SsoService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate(as: 'tên đợt tốt nghiệp')]
    public string $name = '';

    #[Validate(as: 'năm học')]
    public string $school_year = '';

    #[Validate(as: 'số quyết định')]
    public string $certification = '';

    #[Validate(as: 'ngày quyết định')]
    public string $certification_date = '';

    public function mount(): void
    {
        // Set default values
        $this->certification_date = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.graduation.create');
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

        $facultyId = app(SsoService::class)->getFacultyId();

        GraduationCeremony::create([
            'name' => $this->name,
            'school_year' => $this->school_year,
            'certification' => $this->certification,
            'certification_date' => $this->certification_date,
            'faculty_id' => $facultyId,
        ]);

        session()->flash('success', 'Đợt tốt nghiệp đã được tạo thành công.');
        $this->redirect(route('graduation.index'));
    }
}
