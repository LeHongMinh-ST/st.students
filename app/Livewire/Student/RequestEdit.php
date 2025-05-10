<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Enums\StudentUpdateStatus;
use App\Helpers\LogActivityHelper;
use App\Models\Student;
use App\Models\StudentUpdate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class RequestEdit extends Component
{
    use WithFileUploads;

    public Student $student;

    #[Validate(as: 'nơi sinh')]
    public ?string $pob = null;

    #[Validate(as: 'địa chỉ')]
    public ?string $address = null;

    #[Validate(as: 'hộ khẩu thường trú')]
    public ?string $permanent_residence = null;

    #[Validate(as: 'quê quán')]
    public ?string $countryside = null;

    #[Validate(as: 'loại đào tạo')]
    public ?string $training_type = null;

    #[Validate(as: 'số điện thoại')]
    public ?string $phone = null;

    #[Validate(as: 'quốc tịch')]
    public ?string $nationality = null;

    #[Validate(as: 'CCCD/CMND')]
    public ?string $citizen_identification = null;

    #[Validate(as: 'dân tộc')]
    public ?string $ethnic = null;

    #[Validate(as: 'tôn giáo')]
    public ?string $religion = null;

    #[Validate(as: 'ảnh đại diện')]
    public $thumbnail = null;

    #[Validate(as: 'đối tượng chính sách')]
    public ?string $social_policy_object = null;

    #[Validate(as: 'ghi chú')]
    public ?string $note = null;

    #[Validate(as: 'email')]
    public ?string $email = null;

    public bool $hasPendingRequest = false;
    public ?StudentUpdate $pendingRequest = null;

    public function mount(Student $student): void
    {
        $this->student = $student;
        $this->pob = $student->pob;
        $this->address = $student->address;
        $this->permanent_residence = $student->permanent_residence;
        $this->countryside = $student->countryside;
        $this->training_type = $student->training_type->value;
        $this->phone = $student->phone;
        $this->nationality = $student->nationality;
        $this->citizen_identification = $student->citizen_identification;
        $this->ethnic = $student->ethnic;
        $this->religion = $student->religion;
        $this->social_policy_object = $student->social_policy_object->value;
        $this->note = $student->note;
        $this->email = $student->email;

        // Kiểm tra xem sinh viên có yêu cầu chỉnh sửa đang chờ xử lý không
        $this->checkPendingRequests();
    }

    public function render()
    {
        return view('livewire.student.request-edit');
    }

    public function rules(): array
    {
        return [
            'pob' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'permanent_residence' => 'nullable|string|max:255',
            'countryside' => 'nullable|string|max:255',
            'training_type' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'nationality' => 'nullable|string|max:100',
            'citizen_identification' => 'nullable|string|max:20',
            'ethnic' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'thumbnail' => 'nullable|image|max:2048',
            'social_policy_object' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('students', 'email')->ignore($this->student->id),
            ],
        ];
    }

    public function submitRequest(): void
    {
        $this->validate();

        // Kiểm tra lại xem có yêu cầu đang chờ xử lý không
        $this->checkPendingRequests();
        if ($this->hasPendingRequest) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Bạn đã có một yêu cầu chỉnh sửa thông tin đang chờ xử lý. Vui lòng đợi yêu cầu được duyệt hoặc từ chối trước khi tạo yêu cầu mới.'
            ]);
            return;
        }

        // Xác định những trường đã thay đổi
        $changedFields = [];
        $oldData = [
            'email' => $this->student->email,
            'phone' => $this->student->phone,
            'citizen_identification' => $this->student->citizen_identification,
            'pob' => $this->student->pob,
            'address' => $this->student->address,
            'permanent_residence' => $this->student->permanent_residence,
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
            'permanent_residence' => $this->permanent_residence,
            'countryside' => $this->countryside,
            'nationality' => $this->nationality,
            'ethnic' => $this->ethnic,
            'religion' => $this->religion,
            'social_policy_object' => $this->social_policy_object,
            'note' => $this->note,
        ];

        foreach ($newData as $key => $value) {
            if ($oldData[$key] !== $value) {
                $changedFields[$key] = [
                    'old' => $oldData[$key],
                    'new' => $value,
                ];
            }
        }

        // Nếu không có trường nào thay đổi, hiển thị thông báo và không tạo yêu cầu
        if (empty($changedFields) && !$this->thumbnail) {
            $this->dispatch('alert', [
                'type' => 'info',
                'message' => 'Không có thông tin nào được thay đổi.'
            ]);
            return;
        }

        // Tạo yêu cầu chỉnh sửa thông tin
        $updateData = [
            'person_email' => $this->email ?? $this->student->email,
            'gender' => $this->student->gender->value,
            'permanent_residence' => $this->permanent_residence ?? '',
            'dob' => $this->student->dob,
            'pob' => $this->pob ?? '',
            'address' => $this->address ?? '',
            'countryside' => $this->countryside ?? '',
            'training_type' => $this->training_type,
            'phone' => $this->phone ?? '',
            'nationality' => $this->nationality ?? '',
            'citizen_identification' => $this->citizen_identification ?? '',
            'ethnic' => $this->ethnic ?? '',
            'religion' => $this->religion ?? '',
            'social_policy_object' => $this->social_policy_object,
            'note' => $this->note,
            'change_column' => json_encode($changedFields),
            'student_id' => $this->student->id,
            'status' => StudentUpdateStatus::Pending->value,
        ];

        // Xử lý upload ảnh đại diện nếu có
        if ($this->thumbnail) {
            $path = $this->thumbnail->store('thumbnails', 'public');
            $updateData['thumbnail'] = $path;
            $changedFields['thumbnail'] = [
                'old' => $this->student->thumbnail,
                'new' => $path,
            ];
            $updateData['change_column'] = json_encode($changedFields);
        }

        $studentUpdate = StudentUpdate::create($updateData);

        // Ghi log hoạt động
        LogActivityHelper::create(
            'Tạo yêu cầu chỉnh sửa thông tin sinh viên',
            'Sinh viên ' . $this->student->full_name . ' (Mã SV: ' . $this->student->code . ') đã tạo yêu cầu chỉnh sửa thông tin'
        );

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Yêu cầu chỉnh sửa thông tin đã được gửi thành công và đang chờ duyệt.'
        ]);

        // Cập nhật trạng thái yêu cầu đang chờ
        $this->checkPendingRequests();
    }

    private function checkPendingRequests(): void
    {
        $pendingRequest = StudentUpdate::where('student_id', $this->student->id)
            ->whereIn('status', [
                StudentUpdateStatus::Pending->value,
                StudentUpdateStatus::ClassOfficerApproved->value,
                StudentUpdateStatus::TeacherApproved->value,
            ])
            ->latest()
            ->first();

        $this->hasPendingRequest = null !== $pendingRequest;
        $this->pendingRequest = $pendingRequest;
    }
}
