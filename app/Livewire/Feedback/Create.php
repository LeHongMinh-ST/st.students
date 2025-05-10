<?php

declare(strict_types=1);

namespace App\Livewire\Feedback;

use App\Enums\FeedbackStatus;
use App\Helpers\LogActivityHelper;
use App\Models\ClassGenerate;
use App\Models\ClassStudent;
use App\Models\Feedback;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string')]
    public string $content = '';

    #[Validate('required|exists:classes,id')]
    public ?int $class_id = null;

    public function mount(): void
    {
        $user = Auth::user();

        // Chỉ sinh viên lớp trưởng mới có thể tạo phản ánh
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                // Lấy lớp mà sinh viên là lớp trưởng
                $classStudent = ClassStudent::where('student_id', $student->id)
                    ->where('role', \App\Enums\StudentRole::President->value)
                    ->first();

                if ($classStudent) {
                    $this->class_id = $classStudent->class_id;
                } else {
                    // Nếu không phải lớp trưởng, chuyển hướng về trang chủ
                    session()->flash('error', 'Bạn không có quyền tạo phản ánh.');
                    $this->redirect(route('dashboard'));
                }
            }
        } else {
            // Nếu không phải sinh viên, chuyển hướng về trang chủ
            session()->flash('error', 'Chỉ sinh viên lớp trưởng mới có thể tạo phản ánh.');
            $this->redirect(route('dashboard'));
        }
    }

    public function save(): void
    {
        $this->validate();

        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            session()->flash('error', 'Không tìm thấy thông tin sinh viên.');
            return;
        }

        $class = ClassGenerate::find($this->class_id);
        if (!$class) {
            session()->flash('error', 'Không tìm thấy thông tin lớp học.');
            return;
        }

        // Tạo phản ánh mới
        $feedback = Feedback::create([
            'student_id' => $student->id,
            'class_id' => $this->class_id,
            'title' => $this->title,
            'content' => $this->content,
            'status' => FeedbackStatus::Pending,
            'faculty_id' => $class->faculty_id,
        ]);

        // Ghi log hoạt động
        LogActivityHelper::create(
            'Tạo phản ánh',
            'Sinh viên ' . $student->full_name . ' (Mã SV: ' . $student->code . ') đã tạo phản ánh mới: ' . $this->title . ' cho lớp ' . $class->name
        );

        session()->flash('success', 'Phản ánh đã được tạo thành công.');
        $this->redirect(route('feedbacks.index'));
    }

    public function render()
    {
        return view('livewire.feedback.create');
    }
}
