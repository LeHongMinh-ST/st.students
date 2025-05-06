<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Helpers\LogActivityHelper;
use App\Models\Student;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditDetail extends Component
{
    public Student $student;

    #[Validate('nullable|email|max:255')]
    public ?string $email = null;

    #[Validate('nullable|string|max:20')]
    public ?string $phone = null;

    #[Validate('nullable|string|max:20')]
    public ?string $citizen_identification = null;

    #[Validate('nullable|string|max:255')]
    public ?string $pob = null;

    #[Validate('nullable|string|max:255')]
    public ?string $address = null;

    #[Validate('nullable|string|max:255')]
    public ?string $countryside = null;

    #[Validate('nullable|string|max:100')]
    public ?string $nationality = null;

    #[Validate('nullable|string|max:100')]
    public ?string $ethnic = null;

    #[Validate('nullable|string|max:100')]
    public ?string $religion = null;

    #[Validate('nullable|string')]
    public ?string $note = null;

    public ?string $social_policy_object = null;

    public ?string $training_type = null;

    public function mount(Student $student): void
    {
        $this->student = $student;
        $this->loadStudentData();
    }

    public function render()
    {
        return view('livewire.student.edit-detail');
    }

    public function rules(): array
    {
        return [
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'citizen_identification' => 'nullable|string|max:20',
            'pob' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'countryside' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'ethnic' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'social_policy_object' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ];
    }

    public function save(): void
    {
        $this->validate();

        // Lưu dữ liệu cũ trước khi cập nhật
        $oldData = [
            'email' => $this->student->email,
            'phone' => $this->student->phone,
            'citizen_identification' => $this->student->citizen_identification,
            'pob' => $this->student->pob,
            'address' => $this->student->address,
            'countryside' => $this->student->countryside,
            'nationality' => $this->student->nationality,
            'ethnic' => $this->student->ethnic,
            'religion' => $this->student->religion,
            'social_policy_object' => $this->student->social_policy_object->value,
            'note' => $this->student->note,
        ];

        $newData = [
            'email' => $this->email,
            'phone' => $this->phone,
            'citizen_identification' => $this->citizen_identification,
            'pob' => $this->pob,
            'address' => $this->address,
            'countryside' => $this->countryside,
            'nationality' => $this->nationality,
            'ethnic' => $this->ethnic,
            'religion' => $this->religion,
            'social_policy_object' => $this->social_policy_object,
            'note' => $this->note,
        ];

        $this->student->update($newData);

        // Log chi tiết thay đổi
        LogActivityHelper::logChanges(
            'Cập nhật thông tin sinh viên',
            $this->student,
            $oldData,
            $newData
        );

        // Sử dụng toast notification thay vì alert
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Thông tin sinh viên đã được cập nhật thành công.'
        ]);

        $this->redirect(route('students.show', $this->student->id));
    }

    public function cancel(): void
    {
        $this->redirect(route('students.show', $this->student->id));
    }

    /**
     * Tải dữ liệu sinh viên vào các biến
     */
    private function loadStudentData(): void
    {
        $this->email = $this->student->email;
        $this->phone = $this->student->phone;
        $this->citizen_identification = $this->student->citizen_identification;
        $this->pob = $this->student->pob;
        $this->address = $this->student->address;
        $this->countryside = $this->student->countryside;
        $this->nationality = $this->student->nationality;
        $this->ethnic = $this->student->ethnic;
        $this->religion = $this->student->religion;
        $this->note = $this->student->note;
        $this->social_policy_object = $this->student->social_policy_object->value;
        $this->training_type = $this->student->training_type->value;
    }
}
