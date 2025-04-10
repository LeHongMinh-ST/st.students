<?php

declare(strict_types=1);

namespace App\Livewire\Class;

use App\Enums\ClassType;
use App\Enums\Status;
use App\Models\AdmissionYear;
use App\Models\ClassGenerate;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public ClassGenerate $class;

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

    public function mount(ClassGenerate $class): void
    {
        $this->class = $class;
        $this->name = $class->name;
        $this->code = $class->code;
        $this->description = $class->description;
        $this->type = $class->type->value;
        $this->status = $class->status->value;
        $this->admission_year_id = $class->admission_year_id;

        $this->admissionYears = AdmissionYear::all()->toArray();
        $this->classTypes = array_column(ClassType::cases(), 'value');
        $this->statuses = array_column(Status::cases(), 'value');
    }

    public function render()
    {
        return view('livewire.class.edit');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:classes,code,' . $this->class->id,
            'description' => 'required|string',
            'type' => ['required', new Enum(ClassType::class)],
            'status' => ['required', new Enum(Status::class)],
            'admission_year_id' => 'nullable|exists:admission_years,id',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->class->update([
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'type' => $this->type,
            'status' => $this->status,
            'admission_year_id' => $this->admission_year_id,
        ]);

        session()->flash('success', 'Lớp học đã được cập nhật thành công.');
        $this->redirect(route('classes.index'));
    }

    public function delete(): void
    {
        $this->class->delete();
        session()->flash('success', 'Lớp học đã được xóa thành công.');
        $this->redirect(route('classes.index'));
    }
}
