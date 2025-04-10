<?php

declare(strict_types=1);

namespace App\Livewire\Quit;

use App\Helpers\Constants;
use App\Models\Quit;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Quit $quit;

    #[Url(as: 'q')]
    public string $search = '';

    public function mount(Quit $quit): void
    {
        $this->quit = $quit;
    }

    public function render()
    {
        $students = $this->quit->students()
            ->when($this->search, function ($query): void {
                $query->where(function ($q): void {
                    $searchTerm = '%' . $this->search . '%';
                    $q->where('full_name', 'like', $searchTerm)
                        ->orWhere('code', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm);
                });
            })
            ->withPivot(['note_quit', 'quit_type'])
            ->paginate(Constants::PER_PAGE);

        return view('livewire.quit.show', [
            'students' => $students
        ]);
    }

    public function placeholder()
    {
        return view('components.placeholders.table-placeholder');
    }
}
