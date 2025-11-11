<?php

declare(strict_types=1);

namespace App\Livewire\Graduation;

use App\Helpers\LogActivityHelper;
use App\Models\GraduationCeremony;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public GraduationCeremony $ceremony;

    #[Validate(as: 'tên đợt tốt nghiệp')]
    public string $name = '';

    #[Validate(as: 'năm học')]
    public string $school_year = '';

    #[Validate(as: 'số quyết định')]
    public string $certification = '';

    #[Validate(as: 'ngày quyết định')]
    public string $certification_date = '';

    public function mount(GraduationCeremony $ceremony): void
    {
        $this->ceremony = $ceremony;
        $this->name = $ceremony->name;
        $this->school_year = $ceremony->school_year;
        $this->certification = $ceremony->certification;
        $this->certification_date = $ceremony->certification_date
            ? \Carbon\Carbon::parse($ceremony->certification_date)->format('Y-m-d')
            : null;
    }

    public function render()
    {
        return view('livewire.graduation.edit');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'school_year' => 'required|string|max:50',
            'certification' => 'required|string|max:50',
            'certification_date' => 'required|date',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->ceremony->update([
            'name' => $this->name,
            'school_year' => $this->school_year,
            'certification' => $this->certification,
            'certification_date' => $this->certification_date,
        ]);

        // Log the successful update
        LogActivityHelper::create(
            'Cập nhật đợt tốt nghiệp',
            'Cập nhật đợt tốt nghiệp ' . $this->ceremony->name . ' (Năm học: ' . $this->ceremony->school_year . ', Số QĐ: ' . $this->ceremony->certification . ')'
        );

        session()->flash('success', 'Đợt tốt nghiệp đã được cập nhật thành công.');
        $this->redirect(route('graduation.index'));
    }

    public function delete(): void
    {
        $ceremonyName = $this->ceremony->name;
        $schoolYear = $this->ceremony->school_year;
        $certification = $this->ceremony->certification;

        $this->ceremony->delete();

        // Log the successful deletion
        LogActivityHelper::create(
            'Xóa đợt tốt nghiệp',
            'Xóa đợt tốt nghiệp ' . $ceremonyName . ' (Năm học: ' . $schoolYear . ', Số QĐ: ' . $certification . ')'
        );

        session()->flash('success', 'Đợt tốt nghiệp đã được xóa thành công.');
        $this->redirect(route('graduation.index'));
    }
}
