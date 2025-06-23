<?php

declare(strict_types=1);

namespace App\Livewire\Warning;

use App\Helpers\Constants;
use App\Models\Warning;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Warning $warning;

    #[Url(as: 'q')]
    public string $search = '';

    public function mount(Warning $warning): void
    {
        $this->warning = $warning;
    }

    public function render()
    {
        $students = $this->warning->students()
            ->when($this->search, function ($query): void {
                $query->where(function ($q): void {
                    $searchTerm = '%' . $this->search . '%';
                    $q->where('full_name', 'like', $searchTerm)
                        ->orWhere('code', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm);
                });
            })
            ->paginate(Constants::PER_PAGE);

        return view('livewire.warning.show', [
            'students' => $students
        ]);
    }

    public function placeholder()
    {
        return view('components.placeholders.table-placeholder');
    }
}
