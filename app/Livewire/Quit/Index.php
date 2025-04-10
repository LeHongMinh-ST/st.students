<?php

declare(strict_types=1);

namespace App\Livewire\Quit;

use App\Helpers\Constants;
use App\Models\Quit;
use App\Services\SsoService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public function render()
    {
        $facultyId = app(SsoService::class)->getFacultyId();

        $quits = Quit::query()
            ->where('faculty_id', $facultyId)
            ->when($this->search, function ($query): void {
                $query->search($this->search);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE);

        return view('livewire.quit.index', [
            'quits' => $quits
        ]);
    }

    public function placeholder()
    {
        return view('components.placeholders.table-placeholder');
    }
}
