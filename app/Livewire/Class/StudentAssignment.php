<?php

declare(strict_types=1);

namespace App\Livewire\Class;

use App\Enums\Status;
use App\Enums\StudentRole;
use App\Models\ClassGenerate;
use Livewire\Component;
use Livewire\WithPagination;

class StudentAssignment extends Component
{
    use WithPagination;

    public ClassGenerate $class;
    public bool $showModal = false;
    public string $modalTitle = '';
    public string $modalMode = 'create';
    public ?int $editingId = null;

    // Form fields
    public ?int $student_id = null;
    public string $role = '';
    public string $status = '';

    // Lists for dropdowns
    public array $students = [];
    public array $roles = [];
    public array $statuses = [];

    protected $rules = [
        'student_id' => 'required|exists:students,id',
        'role' => 'required|string',
        'status' => 'required|string',
    ];

    protected $validationAttributes = [
        'student_id' => 'sinh viên',
        'role' => 'vai trò',
        'status' => 'trạng thái',
    ];

    public function mount(ClassGenerate $class): void
    {
        $this->class = $class;
        $this->loadStudents();
        $this->loadRoles();
        $this->loadStatuses();

        // Set default role and status
        $this->role = StudentRole::President->value;
        $this->status = Status::Active->value;
    }

    public function render()
    {

        $classStudents = $this->class->students()
            ->wherePivotIn('role', [
                StudentRole::President->value,
                StudentRole::VicePresident->value,
                StudentRole::Secretary->value,
                StudentRole::ViceSecretary->value
            ])
            ->orderBy('class_students.role')
            ->paginate(10);

        return view('livewire.class.student-assignment', [
            'classStudents' => $classStudents
        ]);
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->modalTitle = 'Phân công cán sự lớp';
        $this->modalMode = 'create';
        $this->showModal = true;

        // Phát ra sự kiện để JavaScript có thể mở modal
        $this->dispatch('onOpenStudentAssignmentModal');
    }

    public function openEditModal(int $studentId, string $role): void
    {
        $this->resetForm();
        $this->modalTitle = 'Cập nhật phân công cán sự lớp';
        $this->modalMode = 'edit';

        $classStudent = $this->class->students()
            ->wherePivot('student_id', $studentId)
            ->first();

        if ($classStudent) {
            $this->student_id = $studentId;
            $this->role = $role;
            $this->status = Status::Active->value;
            $this->editingId = $studentId;
        }

        $this->showModal = true;

        // Phát ra sự kiện để JavaScript có thể mở modal
        $this->dispatch('onOpenStudentAssignmentModal');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();

        // Phát ra sự kiện để JavaScript có thể đóng modal
        $this->dispatch('onCloseStudentAssignmentModal');
    }

    public function save(): void
    {
        $this->validate();

        // Kiểm tra xem sinh viên có thuộc lớp không
        $classStudent = $this->class->students()
            ->wherePivot('student_id', $this->student_id)
            ->first();

        if (!$classStudent) {
            $this->addError('student_id', 'Sinh viên không thuộc lớp này.');
            return;
        }

        // Kiểm tra xem đã có ai giữ vai trò này chưa (nếu là vai trò duy nhất)
        if (in_array($this->role, [StudentRole::President->value, StudentRole::Secretary->value])) {
            $existingStudent = $this->class->students()
                ->wherePivot('role', $this->role)
                ->wherePivot('student_id', '!=', $this->student_id)
                ->first();

            if ($existingStudent) {
                // Nếu đã có người giữ vai trò này, chuyển họ về vai trò sinh viên thường
                $this->class->students()->updateExistingPivot(
                    $existingStudent->id,
                    ['role' => StudentRole::Basic->value]
                );
            }
        }

        // Cập nhật vai trò cho sinh viên
        $this->class->students()->updateExistingPivot(
            $this->student_id,
            ['role' => $this->role]
        );

        session()->flash('success', 'Phân công cán sự lớp thành công.');

        // Đóng modal
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('onCloseStudentAssignmentModal');

        // Refresh the class roles in the parent component
        $this->dispatch('student-assignment-updated');
    }

    public function confirmRemoveRole(int $studentId): void
    {
        $this->dispatch('openRemoveRoleConfirmation', ['studentId' => $studentId]);
    }

    public function removeRole(int $studentId): void
    {
        // Chuyển sinh viên về vai trò sinh viên thường
        $this->class->students()->updateExistingPivot(
            $studentId,
            ['role' => StudentRole::Basic->value]
        );

        session()->flash('success', 'Đã xóa vai trò cán sự lớp.');

        // Refresh the class roles in the parent component
        $this->dispatch('student-assignment-updated');
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->student_id = null;
        $this->role = StudentRole::President->value;
        $this->status = Status::Active->value;
        $this->resetErrorBag();
    }

    private function loadStudents(): void
    {
        $this->students = $this->class->students()
            ->where('students.status', '!=', 'graduated')
            ->get()
            ->map(fn ($student) => [
                'id' => $student->id,
                'name' => $student->full_name
            ])
            ->toArray();
    }

    private function loadRoles(): void
    {
        $this->roles = [
            ['value' => StudentRole::President->value, 'label' => StudentRole::President->label()],
            ['value' => StudentRole::VicePresident->value, 'label' => StudentRole::VicePresident->label()],
            ['value' => StudentRole::Secretary->value, 'label' => StudentRole::Secretary->label()],
            ['value' => StudentRole::ViceSecretary->value, 'label' => StudentRole::ViceSecretary->label()],
        ];
    }

    private function loadStatuses(): void
    {
        $this->statuses = [
            ['value' => Status::Active->value, 'label' => 'Hiện tại'],
            ['value' => Status::Inactive->value, 'label' => 'Trước đây']
        ];
    }
}
