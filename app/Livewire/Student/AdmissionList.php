<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Enums\StudentStatus;
use App\Models\AdmissionYear;
use App\Services\SsoService;
use Livewire\Component;
use Livewire\WithPagination;

class AdmissionList extends Component
{
    use WithPagination;

    public $perPage = 12;

    public function render()
    {
        $faculty = app(SsoService::class)->getFacultyId();
        if (!$faculty) {
            return view('livewire.student.admission-list', ['admissionYears' => collect()]);
        }
        $admissionYears = AdmissionYear::query()
            ->withCount([
                'students' => function ($query) use ($faculty): void {
                    $query->where('faculty_id', $faculty);
                },
                'students as currently_studying_count' => function ($query) use ($faculty): void {
                    $query->where('faculty_id', $faculty)
                        ->where('status', StudentStatus::CurrentlyStudying);
                },
            ])
            ->orderBy('admission_year', 'desc')
            ->paginate($this->perPage);

        return view('livewire.student.admission-list', compact('admissionYears'));
    }

    public function loadMore(): void
    {
        $this->perPage += 12;
    }

    public function setAdmissionYear($admissionYear)
    {
        return redirect()->route('students.index', ['admission_year' => $admissionYear]);
    }
}
