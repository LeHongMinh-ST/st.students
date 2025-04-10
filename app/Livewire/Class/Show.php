<?php

declare(strict_types=1);

namespace App\Livewire\Class;

use App\Helpers\Constants;
use App\Models\ClassGenerate;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public ClassGenerate $class;

    #[Url(as: 'q')]
    public string $search = '';

    public string $tab = 'students';

    public function mount(ClassGenerate $class): void
    {
        $this->class = $class;
    }

    public function render()
    {
        $students = $this->class->students()
            ->when($this->search, function ($query) {
                $query->search($this->search);
            })
            ->paginate(Constants::PER_PAGE);

        return view('livewire.class.show', [
            'students' => $students
        ]);
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }
}
