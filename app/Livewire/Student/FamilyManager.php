<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Enums\FamilyRelationship;
use App\Helpers\LogActivityHelper;
use App\Models\Family;
use App\Models\Student;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FamilyManager extends Component
{
    public Student $student;

    public bool $showAddModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;

    public ?int $editingFamilyId = null;
    public ?int $deletingFamilyId = null;

    #[Validate('required')]
    public string $relationship = '';

    #[Validate('required|string|max:255')]
    public string $full_name = '';

    #[Validate('nullable|string|max:255')]
    public ?string $job = null;

    #[Validate('nullable|string|max:20')]
    public ?string $phone = null;

    public function mount(Student $student): void
    {
        $this->student = $student;
    }

    public function render()
    {
        $families = $this->student->families;

        return view('livewire.student.family-manager', [
            'families' => $families,
            'relationships' => FamilyRelationship::cases(),
        ]);
    }

    public function openAddModal(): void
    {
        // Kiểm tra quyền
        Gate::authorize('create', [Family::class, $this->student]);

        $this->resetForm();
        $this->showAddModal = true;
    }

    public function openEditModal(int $familyId): void
    {
        $family = Family::findOrFail($familyId);

        // Kiểm tra quyền
        Gate::authorize('update', $family);

        $this->editingFamilyId = $familyId;
        $this->relationship = $family->relationship->value;
        $this->full_name = $family->full_name ?? '';
        $this->job = $family->job;
        $this->phone = $family->phone;

        $this->showEditModal = true;
    }

    public function openDeleteModal(int $familyId): void
    {
        $family = Family::findOrFail($familyId);

        // Kiểm tra quyền
        Gate::authorize('delete', $family);

        $this->deletingFamilyId = $familyId;
        $this->showDeleteModal = true;
    }

    public function closeModal(): void
    {
        $this->showAddModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->reset(['relationship', 'full_name', 'job', 'phone', 'editingFamilyId', 'deletingFamilyId']);
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'relationship' => $this->relationship,
            'full_name' => $this->full_name,
            'job' => $this->job,
            'phone' => $this->phone,
            'student_id' => $this->student->id,
        ];

        if ($this->showEditModal && $this->editingFamilyId) {
            $family = Family::findOrFail($this->editingFamilyId);

            // Lưu dữ liệu cũ trước khi cập nhật
            $oldData = [
                'relationship' => $family->relationship->value,
                'full_name' => $family->full_name,
                'job' => $family->job,
                'phone' => $family->phone,
            ];

            $family->update($data);

            // Log chi tiết thay đổi
            LogActivityHelper::logChanges(
                'Cập nhật thông tin gia đình sinh viên',
                $family,
                $oldData,
                $data
            );

            $this->dispatch(
                'alert',
                type: 'success',
                message: 'Cập nhật thông tin gia đình thành công.'
            );
        } else {
            $family = Family::create($data);

            // Log hoạt động thêm mới
            LogActivityHelper::create(
                'Thêm thông tin gia đình sinh viên',
                'Thêm thông tin gia đình cho sinh viên ' . $this->student->full_name . ' (Mã SV: ' . $this->student->code . ')'
            );

            $this->dispatch(
                'alert',
                type: 'success',
                message: 'Thêm thông tin gia đình thành công.'
            );
        }

        $this->closeModal();
    }

    public function delete(): void
    {
        if (!$this->deletingFamilyId) {
            return;
        }

        $family = Family::findOrFail($this->deletingFamilyId);

        // Lưu thông tin trước khi xóa để log
        $familyInfo = "Thành viên: {$family->full_name}, Mối quan hệ: {$family->relationship->label()}";

        $family->delete();

        // Log hoạt động xóa
        LogActivityHelper::create(
            'Xóa thông tin gia đình sinh viên',
            'Xóa thông tin gia đình của sinh viên ' . $this->student->full_name . ' (Mã SV: ' . $this->student->code . '). ' . $familyInfo
        );

        $this->dispatch(
            'alert',
            type: 'success',
            message: 'Xóa thông tin gia đình thành công.'
        );

        $this->closeModal();
    }
}
