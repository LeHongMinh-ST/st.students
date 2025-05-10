<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Enums\StudentUpdateStatus;
use App\Helpers\LogActivityHelper;
use App\Models\ApproveStudentUpdates;
use App\Models\StudentUpdate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class TeacherApprovals extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $showModal = false;
    public $modalMode = 'view';
    public $modalTitle = '';

    public ?StudentUpdate $currentRequest = null;
    public int $currentRequestId = 0;

    #[Validate('nullable|string|max:255')]
    public $note = '';

    public function render()
    {
        $user = Auth::user();

        // Lấy danh sách lớp mà giáo viên này là chủ nhiệm
        $classIds = DB::table('class_assigns')
            ->where('teacher_id', $user->id)
            ->pluck('class_id')
            ->toArray();

        if (empty($classIds)) {
            return view('livewire.student.teacher-approvals', [
                'requests' => StudentUpdate::where('id', 0)->paginate(10), // Empty paginator
            ]);
        }

        // Lấy danh sách sinh viên trong các lớp này
        $studentIds = DB::table('class_students')
            ->whereIn('class_id', $classIds)
            ->pluck('student_id')
            ->toArray();

        // Lấy danh sách yêu cầu chỉnh sửa thông tin từ các sinh viên này
        $requests = StudentUpdate::whereIn('student_id', $studentIds)
            ->when($this->search, function ($query): void {
                $query->whereHas('student', function ($q): void {
                    $q->where('full_name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query): void {
                $query->where('status', $this->status);
            })
            // Nếu không có lọc theo trạng thái, mặc định chỉ hiển thị các yêu cầu đã được lớp trưởng duyệt
            ->when(empty($this->status), function ($query): void {
                $query->where('status', StudentUpdateStatus::ClassOfficerApproved->value);
            })
            ->with('student')
            ->latest()
            ->paginate(10);

        return view('livewire.student.teacher-approvals', [
            'requests' => $requests,
        ]);
    }

    public function viewRequest(int $requestId): void
    {
        $this->currentRequestId = $requestId;
        $this->currentRequest = StudentUpdate::with('student')->find($requestId);
        $this->modalTitle = 'Chi tiết yêu cầu chỉnh sửa thông tin';
        $this->modalMode = 'view';
        $this->note = '';
        $this->showModal = true;
    }

    public function approveRequest(): void
    {
        $this->validate([
            'note' => 'nullable|string|max:255',
        ]);

        if (!$this->currentRequest) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Không tìm thấy yêu cầu chỉnh sửa thông tin.'
            ]);
            return;
        }

        // Kiểm tra xem yêu cầu có đang ở trạng thái đã được lớp trưởng duyệt không
        if (StudentUpdateStatus::ClassOfficerApproved !== $this->currentRequest->status) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Yêu cầu này chưa được lớp trưởng duyệt hoặc đã được xử lý.'
            ]);
            return;
        }

        // Cập nhật trạng thái yêu cầu
        $this->currentRequest->status = StudentUpdateStatus::TeacherApproved;
        $this->currentRequest->save();

        // Lưu thông tin người duyệt
        $user = Auth::user();

        ApproveStudentUpdates::create([
            'approveable_type' => User::class,
            'approveable_id' => $user->id,
            'status' => StudentUpdateStatus::TeacherApproved,
            'student_info_updates_id' => $this->currentRequest->id,
            'note' => $this->note,
        ]);

        // Ghi log hoạt động
        LogActivityHelper::create(
            'Duyệt yêu cầu chỉnh sửa thông tin sinh viên',
            'Giáo viên ' . $user->full_name . ' đã duyệt yêu cầu chỉnh sửa thông tin của sinh viên ' .
            $this->currentRequest->student->full_name . ' (Mã SV: ' . $this->currentRequest->student->code . ')'
        );

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Yêu cầu chỉnh sửa thông tin đã được duyệt thành công.'
        ]);

        $this->showModal = false;
        $this->reset(['note']);
    }

    public function rejectRequest(): void
    {
        $this->validate([
            'note' => 'required|string|max:255',
        ]);

        if (!$this->currentRequest) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Không tìm thấy yêu cầu chỉnh sửa thông tin.'
            ]);
            return;
        }

        // Kiểm tra xem yêu cầu có đang ở trạng thái đã được lớp trưởng duyệt không
        if (StudentUpdateStatus::ClassOfficerApproved !== $this->currentRequest->status) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Yêu cầu này chưa được lớp trưởng duyệt hoặc đã được xử lý.'
            ]);
            return;
        }

        // Cập nhật trạng thái yêu cầu
        $this->currentRequest->status = StudentUpdateStatus::Reject;
        $this->currentRequest->save();

        // Lưu thông tin người từ chối
        $user = Auth::user();

        ApproveStudentUpdates::create([
            'approveable_type' => User::class,
            'approveable_id' => $user->id,
            'status' => StudentUpdateStatus::Reject,
            'student_info_updates_id' => $this->currentRequest->id,
            'note' => $this->note,
        ]);

        // Ghi log hoạt động
        LogActivityHelper::create(
            'Từ chối yêu cầu chỉnh sửa thông tin sinh viên',
            'Giáo viên ' . $user->full_name . ' đã từ chối yêu cầu chỉnh sửa thông tin của sinh viên ' .
            $this->currentRequest->student->full_name . ' (Mã SV: ' . $this->currentRequest->student->code . ')'
        );

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Yêu cầu chỉnh sửa thông tin đã bị từ chối.'
        ]);

        $this->showModal = false;
        $this->reset(['note']);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['note']);
    }
}
