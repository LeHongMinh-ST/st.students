<?php

declare(strict_types=1);

namespace App\Livewire\Quit;

use App\Models\Quit;
use App\Models\Semester;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public Quit $quit;

    #[Validate(as: 'tên đợt buộc thôi học')]
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

    public function mount(Quit $quit): void
    {
        $this->quit = $quit;
        $this->name = $quit->name;
        $this->semester_id = $quit->semester_id;
        $this->school_year = $quit->school_year;
        $this->decision_number = $quit->decision_number;
        $this->decision_date = $quit->decision_date->format('Y-m-d');

        $this->semesters = Semester::all()->toArray();
    }

    public function render()
    {
        return view('livewire.quit.edit');
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

        $this->quit->update([
            'name' => $this->name,
            'semester_id' => $this->semester_id,
            'school_year' => $this->school_year,
            'decision_number' => $this->decision_number,
            'decision_date' => $this->decision_date,
        ]);

        session()->flash('success', 'Đợt buộc thôi học đã được cập nhật thành công.');
        $this->redirect(route('quits.index'));
    }

    public function delete(): void
    {
        $this->quit->delete();
        session()->flash('success', 'Đợt buộc thôi học đã được xóa thành công.');
        $this->redirect(route('quits.index'));
    }
}
