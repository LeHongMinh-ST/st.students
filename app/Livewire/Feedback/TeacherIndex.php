<?php

declare(strict_types=1);

namespace App\Livewire\Feedback;

use App\Enums\FeedbackStatus;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TeacherIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    public function render()
    {
        $user = Auth::user();
        $query = Feedback::query();

        // Nếu là giáo viên, chỉ hiển thị phản ánh của lớp mình chủ nhiệm
        if ($user->hasPermission('class.teacher') && !$user->isSuperAdmin()) {
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

        return view('livewire.feedback.teacher-index', [
            'feedbacks' => $feedbacks,
            'statuses' => FeedbackStatus::cases(),
        ]);
    }
}
