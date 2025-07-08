<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Helpers\LogActivityHelper;
use App\Models\Student;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Student $student;

    #[Validate(as: 'nơi sinh')]
    public ?string $pob = null;

    #[Validate(as: 'địa chỉ')]
    public ?string $address = null;

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

    public function mount(Student $student): void
    {
        $this->student = $student;
        $this->pob = $student->pob;
        $this->address = $student->address;
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
    }

    public function render()
    {
        return view('livewire.student.edit');
    }

    public function rules(): array
    {
        return [
            'pob' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
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
            'email' => 'nullable|email|max:255',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'pob' => $this->pob,
            'address' => $this->address,
            'countryside' => $this->countryside,
            'training_type' => $this->training_type,
            'phone' => $this->phone,
            'nationality' => $this->nationality,
            'citizen_identification' => $this->citizen_identification,
            'ethnic' => $this->ethnic,
            'religion' => $this->religion,
            'social_policy_object' => $this->social_policy_object,
            'note' => $this->note,
            'email' => $this->email,
        ];

        // Handle thumbnail upload if provided
        if ($this->thumbnail) {
            $path = $this->thumbnail->store('thumbnails', 'public');
            $data['thumbnail'] = $path;
        }

        $oldData = $this->student->getAttributes();

        $this->student->update($data);

        $detailLog = $this->getDetailLog($this->checkChangeStatus($oldData, $data));

        // Log the successful student update
        LogActivityHelper::create(
            'Cập nhật sinh viên',
            'Cập nhật thông tin sinh viên ' . $this->student->full_name . ' (Mã SV: ' . $this->student->code . ') ' . $detailLog
        );

        session()->flash('success', 'Thông tin sinh viên đã được cập nhật thành công.');
        $this->redirect(route('students.show', $this->student->id));
    }

    /**
     * Compare old and new data to find changed keys and values.
     *
     * @param array $oldData The original data array.
     * @param array $newData The updated data array.
     * @return array An array containing the old and new values for changed keys.
     */
    private function checkChangeStatus(array $oldData, array $newData): array
    {
        // Initialize an array to store changes
        $changes = [];

        // Define the fields that are enums and need to use getLabel()
        $enumFields = ['status', 'social_policy_object', 'gender', 'training_type'];

        // Iterate over the new data to find changes
        foreach ($newData as $key => $newValue) {
            // Check if the value has changed compared to the old data
            if (array_key_exists($key, $oldData) && $oldData[$key] !== $newValue) {
                // If the field is an enum, get the label for both old and new values
                if (in_array($key, $enumFields)) {
                    $oldValue = $oldData[$key]->getLabel();
                    $newValue = $newValue->getLabel();
                } else {
                    $oldValue = $oldData[$key];
                }

                // Store the changed key with old and new values
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue
                ];
            }
        }

        return $changes;
    }

    private function getDetailLog(array $changes): string
    {
        $detailLog = '';
        foreach ($changes as $key => $change) {
            $detailLog .= $this->getKeyLabel($key) . ' từ ' . $change['old'] . ' sang ' . $change['new'] . ', ';
        }
        return $detailLog;
    }

    private function getKeyLabel(string $key): string
    {
        return match ($key) {
            'status' => 'trạng thái',
            'social_policy_object' => 'đối tượng chính sách',
            'gender' => 'giới tính',
            'training_type' => 'loại hình đào tạo',
            'pob' => 'nơi sinh',
            'address' => 'địa chỉ',
            'countryside' => 'quê quán',
            'phone' => 'số điện thoại',
            'nationality' => 'quốc tịch',
            'citizen_identification' => 'CMND/CCCD',
            'ethnic' => 'dân tộc',
            'religion' => 'tôn giáo',
            'thumbnail' => 'ảnh đại diện',
            'note' => 'ghi chú',
            'email' => 'email',
            default => 'không xác định',
        };
    }

}
