<?php

declare(strict_types=1);

namespace App\Livewire\Quit;

use App\Models\Quit;
use App\Models\Semester;
use App\Services\SsoService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
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

    public function mount(): void
    {
        // Set default values
        $this->decision_date = now()->format('Y-m-d');
        $this->semesters = Semester::all()->toArray();
    }

    public function render()
    {
        return view('livewire.quit.create');
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

        $facultyId = app(SsoService::class)->getFacultyId();

        Quit::create([
            'name' => $this->name,
            'semester_id' => $this->semester_id,
            'school_year' => $this->school_year,
            'decision_number' => $this->decision_number,
            'decision_date' => $this->decision_date,
            'faculty_id' => $facultyId,
        ]);

        session()->flash('success', 'Đợt buộc thôi học đã được tạo thành công.');
        $this->redirect(route('quits.index'));
    }
}
