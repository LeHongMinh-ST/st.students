<?php

declare(strict_types=1);

namespace App\Livewire\Feedback;

use App\Enums\FeedbackStatus;
use App\Helpers\LogActivityHelper;
use App\Models\Feedback;
use App\Models\FeedbackReply;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Show extends Component
{
    public Feedback $feedback;

    #[Validate('required|string')]
    public string $replyContent = '';

    #[Validate('nullable|string')]
    public ?string $newStatus = null;

    public function mount(Feedback $feedback): void
    {
        $this->feedback = $feedback;
    }

    public function addReply(): void
    {
        $this->validate(['replyContent' => 'required|string']);

        // Kiểm tra quyền phản hồi
        if (!Auth::user()->can('reply', $this->feedback)) {
            session()->flash('error', 'Bạn không có quyền phản hồi phản ánh này.');
            return;
        }

        // Tạo phản hồi mới
        $reply = FeedbackReply::create([
            'feedback_id' => $this->feedback->id,
            'user_id' => Auth::id(),
            'content' => $this->replyContent,
        ]);

        // Lấy thông tin người dùng và phản ánh
        $user = Auth::user();
        $student = $this->feedback->student;
        $class = $this->feedback->class;

        // Ghi log hoạt động
        LogActivityHelper::create(
            'Phản hồi phản ánh',
            $user->full_name . ' đã phản hồi phản ánh của sinh viên ' .
            $student->full_name . ' (Mã SV: ' . $student->code . ') với tiêu đề: ' .
            $this->feedback->title . ' của lớp ' . $class->name
        );

        // Cập nhật trạng thái phản ánh thành đang xử lý nếu đang ở trạng thái chờ xử lý
        if (FeedbackStatus::Pending === $this->feedback->status) {
            $this->feedback->update(['status' => FeedbackStatus::Processing]);
        }

        $this->replyContent = '';
        session()->flash('success', 'Phản hồi đã được gửi thành công.');
    }

    public function updateStatus(): void
    {
        $this->validate(['newStatus' => 'required|string']);

        // Kiểm tra quyền cập nhật trạng thái
        if (!Auth::user()->can('updateStatus', $this->feedback)) {
            session()->flash('error', 'Bạn không có quyền cập nhật trạng thái phản ánh này.');
            return;
        }

        // Lưu trạng thái cũ để ghi log
        $oldStatus = $this->feedback->status;

        // Cập nhật trạng thái
        $this->feedback->update(['status' => $this->newStatus]);

        // Lấy thông tin người dùng và phản ánh
        $user = Auth::user();
        $student = $this->feedback->student;
        $class = $this->feedback->class;
        $newStatus = FeedbackStatus::from($this->newStatus);

        // Ghi log hoạt động
        LogActivityHelper::create(
            'Cập nhật trạng thái phản ánh',
            $user->full_name . ' đã cập nhật trạng thái phản ánh của sinh viên ' .
            $student->full_name . ' (Mã SV: ' . $student->code . ') với tiêu đề: ' .
            $this->feedback->title . ' của lớp ' . $class->name . ' từ ' .
            $oldStatus->label() . ' sang ' . $newStatus->label()
        );

        session()->flash('success', 'Trạng thái phản ánh đã được cập nhật thành công.');
    }

    public function render()
    {
        return view('livewire.feedback.show', [
            'replies' => $this->feedback->replies()->with('user')->latest()->get(),
            'statuses' => FeedbackStatus::cases(),
        ]);
    }
}
