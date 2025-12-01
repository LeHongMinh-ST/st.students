<?php

declare(strict_types=1);

namespace App\Livewire\Graduation;

use App\Helpers\Constants;
use App\Models\GraduationCeremony;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public GraduationCeremony $ceremony;

    #[Url(as: 'q')]
    public string $search = '';

    public function mount(GraduationCeremony $ceremony): void
    {
        $this->ceremony = $ceremony;
    }

    public function render()
    {
        $students = $this->ceremony->students()
            ->when($this->search, function ($query): void {
                $query->where(function ($q): void {
                    $searchTerm = '%' . $this->search . '%';
                    $q->where('full_name', 'like', $searchTerm)
                        ->orWhere('code', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm);
                });
            })
            ->withPivot(['gpa', 'rank', 'email','industry_code','industry_name'])
            ->paginate(Constants::PER_PAGE);

        return view('livewire.graduation.show', [
            'students' => $students
        ]);
    }

    public function placeholder()
    {
        return view('components.placeholders.table-placeholder');
    }
}
