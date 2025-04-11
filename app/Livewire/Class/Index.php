<?php

declare(strict_types=1);

namespace App\Livewire\Class;

use App\Helpers\Constants;
use App\Models\ClassGenerate;
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

        $classes = ClassGenerate::query()
            ->where('faculty_id', $facultyId)
            ->when($this->search, function ($query): void {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm): void {
                    $q->where('name', 'like', $searchTerm)
                        ->orWhere('code', 'like', $searchTerm);
                });
            })
            ->withCount('students')
            ->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE);

        // Lấy thông tin giáo viên chủ nhiệm và cố vấn học tập cho từng lớp
        $classIds = $classes->pluck('id')->toArray();
        $classAssigns = [];

        if (!empty($classIds)) {
            // Lấy phân công giáo viên mới nhất cho mỗi lớp
            $assigns = \App\Models\ClassAssign::whereIn('class_id', $classIds)
                ->where('status', 'active')
                ->with(['teacher', 'subTeacher'])
                ->get()
                ->groupBy('class_id');

            foreach ($assigns as $classId => $classAssignments) {
                // Lấy phân công mới nhất
                $latestAssignment = $classAssignments->sortByDesc('year')->first();
                $classAssigns[$classId] = [
                    'teacher' => $latestAssignment->teacher ? $latestAssignment->teacher->full_name : null,
                    'sub_teacher' => $latestAssignment->subTeacher ? $latestAssignment->subTeacher->full_name : null,
                ];
            }
        }

        return view('livewire.class.index', [
            'classes' => $classes,
            'classAssigns' => $classAssigns
        ]);
    }

    public function placeholder()
    {
        return view('components.placeholders.table-placeholder');
    }
}
