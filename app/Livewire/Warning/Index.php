<?php

declare(strict_types=1);

namespace App\Livewire\Warning;

use App\Helpers\Constants;
use App\Models\Warning;
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

        $warnings = Warning::query()
            ->where('faculty_id', $facultyId)
            ->when($this->search, function ($query): void {
                $query->search($this->search);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE);

        return view('livewire.warning.index', [
            'warnings' => $warnings
        ]);
    }

    public function placeholder()
    {
        return view('components.placeholders.table-placeholder');
    }
}
