<?php

declare(strict_types=1);

namespace App\Livewire\Warning;

use App\Models\Semester;
use App\Models\Warning;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public Warning $warning;

    #[Validate(as: 'tên đợt cảnh báo')]
    public string $name = '';

    #[Validate(as: 'học kỳ')]
    public ?int $semester_id = null;

    #[Validate(as: 'năm học')]
    public string $school_year = '';

    #[Validate(as: 'số quyết định')]
    public string $decision_number = '';

    #[Validate(as: 'ngày quyết định')]
    public string $decision_date = '';

    public array $semesters = [];

    public function mount(Warning $warning): void
    {
        $this->warning = $warning;
        $this->name = $warning->name;
        $this->semester_id = $warning->semester_id;
        $this->school_year = $warning->school_year;
        $this->decision_number = $warning->decision_number;
        $this->decision_date = $warning->decision_date->format('Y-m-d');

        $this->semesters = Semester::all()->toArray();
    }

    public function render()
    {
        return view('livewire.warning.edit');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'semester_id' => 'required|exists:semesters,id',
            'school_year' => 'required|string|max:50',
            'decision_number' => 'required|string|max:50',
            'decision_date' => 'required|date',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->warning->update([
            'name' => $this->name,
            'semester_id' => $this->semester_id,
            'school_year' => $this->school_year,
            'decision_number' => $this->decision_number,
            'decision_date' => $this->decision_date,
        ]);

        session()->flash('success', 'Đợt cảnh báo đã được cập nhật thành công.');
        $this->redirect(route('warnings.index'));
    }

    public function delete(): void
    {
        $this->warning->delete();
        session()->flash('success', 'Đợt cảnh báo đã được xóa thành công.');
        $this->redirect(route('warnings.index'));
    }
}
