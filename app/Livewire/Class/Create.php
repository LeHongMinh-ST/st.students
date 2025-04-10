<?php

declare(strict_types=1);

namespace App\Livewire\Class;

use App\Enums\ClassType;
use App\Enums\Status;
use App\Models\AdmissionYear;
use App\Models\ClassGenerate;
use App\Services\SsoService;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate(as: 'tên lớp')]
    public string $name = '';

    #[Validate(as: 'mã lớp')]
    public string $code = '';

    #[Validate(as: 'mô tả')]
    public string $description = '';

    #[Validate(as: 'loại lớp')]
    public string $type = '';

    #[Validate(as: 'trạng thái')]
    public string $status = '';

    #[Validate(as: 'khóa học')]
    public ?int $admission_year_id = null;

    public array $admissionYears = [];
    public array $classTypes = [];
    public array $statuses = [];

    public function mount(): void
    {
        $this->admissionYears = AdmissionYear::all()->toArray();
        $this->classTypes = array_column(ClassType::cases(), 'value');
        $this->statuses = array_column(Status::cases(), 'value');
        
        // Set default values
        $this->type = ClassType::Basic->value;
        $this->status = Status::Active->value;
    }

    public function render()
    {
        return view('livewire.class.create');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:classes,code',
            'description' => 'required|string',
            'type' => ['required', new Enum(ClassType::class)],
            'status' => ['required', new Enum(Status::class)],
            'admission_year_id' => 'nullable|exists:admission_years,id',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $facultyId = app(SsoService::class)->getFacultyId();

        ClassGenerate::create([
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'type' => $this->type,
            'status' => $this->status,
            'faculty_id' => $facultyId,
            'admission_year_id' => $this->admission_year_id,
        ]);

        session()->flash('success', 'Lớp học đã được tạo thành công.');
        $this->redirect(route('classes.index'));
    }
}
