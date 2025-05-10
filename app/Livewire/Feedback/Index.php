<?php

declare(strict_types=1);

namespace App\Livewire\Feedback;

use App\Enums\FeedbackStatus;
use App\Helpers\LogActivityHelper;
use App\Models\ClassStudent;
use App\Models\Feedback;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    public function render()
    {
        $user = Auth::user();
        $query = Feedback::query();

        // Nếu là sinh viên lớp trưởng, chỉ hiển thị phản ánh của lớp mình
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                // Lấy danh sách lớp mà sinh viên là lớp trưởng
                $classIds = ClassStudent::where('student_id', $student->id)
                    ->where('role', \App\Enums\StudentRole::President->value)
                    ->pluck('class_id')
                    ->toArray();

                $query->whereIn('class_id', $classIds);
            }
        }
        // Nếu là giáo viên, chỉ hiển thị phản ánh của lớp mình chủ nhiệm
        elseif ($user->hasPermission('class.teacher') && !$user->isSuperAdmin()) {
            // Lấy danh sách lớp mà giáo viên là chủ nhiệm
            $classIds = DB::table('class_assigns')
                ->where('teacher_id', $user->id)
                ->pluck('class_id')
                ->toArray();

            $query->whereIn('class_id', $classIds);
        }

        // Tìm kiếm theo tiêu đề
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // Lọc theo trạng thái
        if ($this->status) {
            $query->where('status', $this->status);
        }

        $feedbacks = $query->with(['student', 'class'])
            ->latest()
            ->paginate(10);

        return view('livewire.feedback.index', [
            'feedbacks' => $feedbacks,
            'statuses' => FeedbackStatus::cases(),
        ]);
    }

    public function deleteFeedback(Feedback $feedback): void
    {
        if (Auth::user()->can('delete', $feedback)) {
            // Lấy thông tin trước khi xóa để ghi log
            $student = $feedback->student;
            $class = $feedback->class;
            $title = $feedback->title;

            $feedback->delete();

            // Ghi log hoạt động
            LogActivityHelper::create(
                'Xóa phản ánh',
                'Sinh viên ' . $student->full_name . ' (Mã SV: ' . $student->code . ') đã xóa phản ánh: ' .
                $title . ' của lớp ' . $class->name
            );

            session()->flash('success', 'Phản ánh đã được xóa thành công.');
        } else {
            session()->flash('error', 'Bạn không có quyền xóa phản ánh này.');
        }
    }
}
