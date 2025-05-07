<?php

declare(strict_types=1);

namespace App\Livewire\Class;

use App\Enums\Status;
use App\Models\ClassAssign;
use App\Models\ClassGenerate;
use App\Models\User;
use App\Services\SsoService;
use Livewire\Component;
use Livewire\WithPagination;

class TeacherAssignment extends Component
{
    use WithPagination;

    public ClassGenerate $class;
    public bool $showModal = false;
    public string $modalTitle = '';
    public string $modalMode = 'create';
    public ?int $editingId = null;

    // Form fields
    public ?int $teacher_id = null;
    public string $year = '';
    public string $status = '';

    // Lists for dropdowns
    public array $teachers = [];
    public array $years = [];
    public array $statuses = [];

    protected $rules = [
        'teacher_id' => 'required|exists:users,id',
        'year' => 'required|string',
        'status' => 'required|string',
    ];

    protected $validationAttributes = [
        'teacher_id' => 'giáo viên chủ nhiệm',
        'year' => 'năm học',
        'status' => 'trạng thái',
    ];

    public function mount(ClassGenerate $class): void
    {
        $this->class = $class;
        $this->loadTeachers();
        $this->loadYears();
        $this->loadStatuses();

        // Set default year to current academic year
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $this->year = $currentYear . '-' . $nextYear;

        // Set default status
        $this->status = Status::Active->value;
    }

    public function render()
    {
        $assignments = ClassAssign::where('class_id', $this->class->id)
            ->with(['teacher', 'subTeacher'])
            ->orderBy('year', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.class.teacher-assignment', [
            'assignments' => $assignments
        ]);
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->modalTitle = 'Phân công giáo viên';
        $this->modalMode = 'create';
        $this->showModal = true;

        // Phát ra sự kiện để JavaScript có thể mở modal
        $this->dispatch('onOpenAssignmentModal');
    }

    public function openEditModal(int $assignmentId): void
    {
        $this->resetForm();
        $this->editingId = $assignmentId;
        $this->modalTitle = 'Cập nhật phân công giáo viên';
        $this->modalMode = 'edit';

        $assignment = ClassAssign::findOrFail($assignmentId);
        $this->teacher_id = $assignment->teacher_id;
        $this->year = $assignment->year;
        $this->status = $assignment->status->value;

        $this->showModal = true;

        // Phát ra sự kiện để JavaScript có thể mở modal
        $this->dispatch('onOpenAssignmentModal');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();

        // Phát ra sự kiện để JavaScript có thể đóng modal
        $this->dispatch('onCloseAssignmentModal');
    }

    public function save(): void
    {
        $this->validate();


        if ('create' === $this->modalMode) {
            // Check if there's already an assignment for this year
            $existingAssignments = ClassAssign::where('class_id', $this->class->id)
                ->where('year', $this->year)
                ->get();

            // If creating a new active assignment, mark all existing assignments as inactive
            if ($this->status === Status::Active->value && $existingAssignments->isNotEmpty()) {
                foreach ($existingAssignments as $existingAssignment) {
                    $existingAssignment->update([
                        'status' => Status::Inactive->value,
                    ]);
                }
            }

            ClassAssign::create([
                'class_id' => $this->class->id,
                'teacher_id' => $this->teacher_id,
                'sub_teacher_id' => null, // Không còn sử dụng cố vấn học tập
                'year' => $this->year,
                'status' => $this->status,
                'assigned_at' => now(),
            ]);

            session()->flash('success', 'Phân công giáo viên thành công.');
        } else {
            $assignment = ClassAssign::findOrFail($this->editingId);

            // If updating to active status, mark all other assignments for this year as inactive
            if ($this->status === Status::Active->value) {
                ClassAssign::where('class_id', $this->class->id)
                    ->where('year', $this->year)
                    ->where('id', '!=', $this->editingId)
                    ->update(['status' => Status::Inactive->value]);
            }

            $assignment->update([
                'teacher_id' => $this->teacher_id,
                'sub_teacher_id' => null, // Không còn sử dụng cố vấn học tập
                'year' => $this->year,
                'status' => $this->status,
                'assigned_at' => now(),
            ]);

            session()->flash('success', 'Cập nhật phân công giáo viên thành công.');
        }

        // Đóng modal
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('onCloseAssignmentModal');

        // Refresh the class roles in the parent component
        $this->dispatch('teacher-assignment-updated');
    }

    public function confirmDelete(int $assignmentId): void
    {
        $this->dispatch('openDeleteConfirmation', ['assignmentId' => $assignmentId]);
    }

    public function deleteAssignment(int $assignmentId): void
    {
        $assignment = ClassAssign::findOrFail($assignmentId);
        $assignment->delete();

        session()->flash('success', 'Xóa phân công giáo viên thành công.');

        // Refresh the class roles in the parent component
        $this->dispatch('teacher-assignment-updated');
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->teacher_id = null;

        // Set default year to current academic year
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $this->year = $currentYear . '-' . $nextYear;

        $this->status = Status::Active->value;
        $this->resetErrorBag();
    }

    private function loadTeachers(): void
    {
        $facultyId = app(SsoService::class)->getFacultyId();

        // Get users with homeroom teacher permissions from the faculty
        $this->teachers = User::where('faculty_id', $facultyId)
            ->where('status', Status::Active)
            ->whereHas('userRoles', function ($query): void {
                $query->whereHas('permissions', function ($q): void {
                    $q->where('code', 'class.teacher');
                });
            })
            ->get()
            ->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->full_name ?? $user->name
            ])
            ->toArray();
    }

    private function loadYears(): void
    {
        $currentYear = (int)date('Y');
        $this->years = [];

        // Generate academic years (current year - 5 to current year + 5)
        for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
            $academicYear = $i . '-' . ($i + 1);
            $this->years[] = [
                'value' => $academicYear,
                'label' => 'Năm học ' . $academicYear
            ];
        }
    }

    private function loadStatuses(): void
    {
        $this->statuses = [
            ['value' => Status::Active->value, 'label' => 'Hiện tại'],
            ['value' => Status::Inactive->value, 'label' => 'Trước đây']
        ];
    }
}
