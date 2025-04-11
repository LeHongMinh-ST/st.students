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
        $userId = auth()->id();

        $classes = ClassGenerate::query()
            ->join('class_assigns', 'classes.id', '=', 'class_assigns.class_id')
            ->where('classes.faculty_id', $facultyId)
            ->where('class_assigns.sub_teacher_id', $userId)
            ->when($this->search, function ($query): void {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm): void {
                    $q->where('classes.name', 'like', $searchTerm)
                        ->orWhere('classes.code', 'like', $searchTerm);
                });
            })
            ->select('classes.*')
            ->orderBy('classes.created_at', 'desc')
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
