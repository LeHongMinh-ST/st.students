<?php

declare(strict_types=1);

namespace App\Livewire\Graduation;

use App\Helpers\Constants;
use App\Models\GraduationCeremony;
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

        $ceremonies = GraduationCeremony::query()
            ->where('faculty_id', $facultyId)
            ->when($this->search, function ($query): void {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm): void {
                    $q->where('name', 'like', $searchTerm)
                        ->orWhere('school_year', 'like', $searchTerm)
                        ->orWhere('certification', 'like', $searchTerm);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE);

        return view('livewire.graduation.index', [
            'ceremonies' => $ceremonies
        ]);
    }

    public function placeholder()
    {
        return view('components.placeholders.table-placeholder');
    }
}
