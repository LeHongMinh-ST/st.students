<?php

declare(strict_types=1);

namespace App\Livewire\Feedback;

use App\Helpers\LogActivityHelper;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public Feedback $feedback;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string')]
    public string $content = '';

    public function mount(Feedback $feedback): void
    {
        $this->feedback = $feedback;
        $this->title = $feedback->title;
        $this->content = $feedback->content;
    }

    public function save(): void
    {
        $this->validate();

        // Kiểm tra quyền sửa phản ánh
        if (!Auth::user()->can('update', $this->feedback)) {
            session()->flash('error', 'Bạn không có quyền sửa phản ánh này.');
            return;
        }

        // Lưu tiêu đề và nội dung cũ để ghi log
        $oldTitle = $this->feedback->title;
        $oldContent = $this->feedback->content;

        // Cập nhật phản ánh
        $this->feedback->update([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        // Lấy thông tin sinh viên
        $student = $this->feedback->student;
        $class = $this->feedback->class;

        // Ghi log hoạt động
        LogActivityHelper::create(
            'Chỉnh sửa phản ánh',
            'Sinh viên ' . $student->full_name . ' (Mã SV: ' . $student->code . ') đã chỉnh sửa phản ánh: ' .
            $oldTitle . ' -> ' . $this->title . ' của lớp ' . $class->name
        );

        session()->flash('success', 'Phản ánh đã được cập nhật thành công.');
        $this->redirect(route('feedbacks.show', $this->feedback->id));
    }

    public function render()
    {
        return view('livewire.feedback.edit');
    }
}
