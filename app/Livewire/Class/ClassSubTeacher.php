<?php

declare(strict_types=1);

namespace App\Livewire\Class;

use App\Helpers\Constants;
use App\Models\ClassGenerate;
use App\Services\SsoService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ClassSubTeacher extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public function render()
    {
        $facultyId = app(SsoService::class)->getFacultyId();

        $classes = ClassGenerate::query()
            ->where('faculty_id', $facultyId)
            ->when($this->search, function ($query): void {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm): void {
                    $q->where('name', 'like', $searchTerm)
                        ->orWhere('code', 'like', $searchTerm);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE);

        return view('livewire.class.class-sub-teacher', [
            'classes' => $classes
        ]);
    }

    public function placeholder()
    {
        return view('components.placeholders.table-placeholder');
    }
}
