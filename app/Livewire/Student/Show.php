<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Enums\StudentRole;
use App\Enums\StudentStatus;
use App\Helpers\Constants;
use App\Helpers\LogActivityHelper;
use App\Models\ClassStudent;
use App\Models\Student;
use App\Models\StudentUpdate;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Student $student;

    public string $tab = 'profile';

    public bool $editStatusMode = false;
    public bool $editInfoMode = false;

    public StudentStatus $studentStatus = StudentStatus::CurrentlyStudying;

    // Các trường thông tin có thể chỉnh sửa
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
        $this->studentStatus = $student->status;
        $this->loadStudentData();
    }

    public function updatedStudentStatus(): void
    {
        $oldStatus = $this->student->status;
        $this->student->status = $this->studentStatus;
        $this->student->save();

        // Log the successful status update
        LogActivityHelper::create(
            'Cập nhật trạng thái sinh viên',
            'Cập nhật trạng thái sinh viên ' . $this->student->full_name . ' (Mã SV: ' . $this->student->code . ') ' .
            'từ ' . $oldStatus->name . ' sang ' . $this->studentStatus->name
        );

        $this->editStatusMode = false;
    }

    public function render()
    {
        $classes = $this->student->classes()
            ->paginate(Constants::PER_PAGE);

        $families = $this->student->families;

        // Lấy danh sách yêu cầu chỉnh sửa thông tin của sinh viên
        $updateRequests = StudentUpdate::where('student_id', $this->student->id)
            ->latest()
            ->paginate(Constants::PER_PAGE, ['*'], 'update_page');

        // Kiểm tra xem người dùng hiện tại có phải là lớp trưởng không
        $isClassMonitor = false;
        $user = Auth::user();

        if ($user && $user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                $isClassMonitor = ClassStudent::where('student_id', $student->id)
                    ->where('role', StudentRole::President->value)
                    ->exists();
            }
        }

        return view('livewire.student.show', [
            'classes' => $classes,
            'families' => $families,
            'updateRequests' => $updateRequests,
            'isClassMonitor' => $isClassMonitor
        ]);
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    /**
     * Bật chế độ chỉnh sửa thông tin
     */
    public function enableEditMode(): void
    {
        $this->editInfoMode = true;
    }

    /**
     * Tắt chế độ chỉnh sửa thông tin
     */
    public function cancelEdit(): void
    {
        $this->editInfoMode = false;
        $this->loadStudentData();
        $this->resetValidation();
    }

    /**
     * Lưu thông tin đã chỉnh sửa
     */
    public function saveInfo(): void
    {
        $this->validate();

        $data = [
            'email' => $this->email,
            'phone' => $this->phone,
            'citizen_identification' => $this->citizen_identification,
            'pob' => $this->pob,
            'address' => $this->address,
            'countryside' => $this->countryside,
            'nationality' => $this->nationality,
            'ethnic' => $this->ethnic,
            'religion' => $this->religion,
            'note' => $this->note,
            'social_policy_object' => $this->social_policy_object,
        ];

        $this->student->update($data);

        // Log the successful info update
        LogActivityHelper::create(
            'Cập nhật thông tin sinh viên',
            'Cập nhật thông tin sinh viên ' . $this->student->full_name . ' (Mã SV: ' . $this->student->code . ')'
        );

        $this->editInfoMode = false;
        session()->flash('success', 'Thông tin sinh viên đã được cập nhật thành công.');
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
