<?php

declare(strict_types=1);

namespace App\Livewire\Student;

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
        $this->training_type = $student->training_type;
        $this->phone = $student->phone;
        $this->nationality = $student->nationality;
        $this->citizen_identification = $student->citizen_identification;
        $this->ethnic = $student->ethnic;
        $this->religion = $student->religion;
        $this->social_policy_object = $student->social_policy_object;
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

        $this->student->update($data);

        session()->flash('success', 'Thông tin sinh viên đã được cập nhật thành công.');
        $this->redirect(route('students.show', $this->student->id));
    }
}
