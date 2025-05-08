<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Helpers\Constants;
use App\Models\AdmissionYear;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class StudentList extends Component
{
    use WithPagination;

    public AdmissionYear|null $admissionYear = null;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'filter')]
    public string $filter = 'all';

    #[Url(as: 'warning')]
    public string $warning = 'all';

    public function render()
    {
        $students = $this->admissionYear->students()
            ->when($this->search, function ($query): void {
                $query->search($this->search);
            })
            ->when('all' !== $this->filter, function ($query): void {
                switch ($this->filter) {
                    case 'currently_studying':
                        $query->where('status', 'currently_studying');
                        break;
                    case 'graduated':
                        $query->where('status', 'graduated');
                        break;
                    case 'deferred':
                        $query->where('status', 'deferred');
                        break;
                    case 'temporarily_suspended':
                        $query->where('status', 'temporarily_suspended');
                        break;
                    case 'expelled':
                        $query->where('status', 'expelled');
                        break;
                    case 'to_drop_out':
                        $query->where('status', 'to_drop_out');
                        break;
                }
            })
            ->when('all' !== $this->warning, function ($query): void {
                if ('has_warning' === $this->warning) {
                    $query->whereHas('warnings');
                } else {
                    $query->whereDoesntHave('warnings');
                }
            })
            ->paginate(Constants::PER_PAGE);

        return view('livewire.student.student-list', [
            'students' => $students
        ]);
    }

    public function mount(AdmissionYear $admissionYear): void
    {
        $this->admissionYear = $admissionYear;
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function setWarning(string $warning): void
    {
        $this->warning = $warning;
        $this->resetPage();
    }
}
