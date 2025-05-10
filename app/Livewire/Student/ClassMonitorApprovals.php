<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Enums\StudentRole;
use App\Enums\StudentUpdateStatus;
use App\Helpers\LogActivityHelper;
use App\Models\ApproveStudentUpdates;
use App\Models\ClassStudent;
use App\Models\Student;
use App\Models\StudentUpdate;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class ClassMonitorApprovals extends Component
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

    public function mount(): void
    {
        // Kiểm tra xem người dùng hiện tại có phải là lớp trưởng không
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return;
        }

        $isClassMonitor = ClassStudent::where('student_id', $student->id)
            ->where('role', StudentRole::President->value)
            ->exists();

        if (!$isClassMonitor) {
            return;
        }
    }

    public function render()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return view('livewire.student.class-monitor-approvals', [
                'requests' => StudentUpdate::where('id', 0)->paginate(10), // Empty paginator
            ]);
        }

        // Lấy danh sách lớp mà sinh viên này là lớp trưởng
        $classIds = ClassStudent::where('student_id', $student->id)
            ->where('role', StudentRole::President->value)
            ->pluck('class_id')
            ->toArray();

        if (empty($classIds)) {
            return view('livewire.student.class-monitor-approvals', [
                'requests' => StudentUpdate::where('id', 0)->paginate(10), // Empty paginator
            ]);
        }

        // Lấy danh sách sinh viên trong các lớp này
        $studentIds = ClassStudent::whereIn('class_id', $classIds)
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
            // Nếu không có lọc theo trạng thái, mặc định chỉ hiển thị các yêu cầu đang chờ duyệt
            ->when(empty($this->status), function ($query): void {
                $query->where('status', StudentUpdateStatus::Pending->value);
            })
            ->with('student')
            ->latest()
            ->paginate(10);

        return view('livewire.student.class-monitor-approvals', [
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

        // Kiểm tra xem yêu cầu có đang ở trạng thái chờ duyệt không
        if (StudentUpdateStatus::Pending !== $this->currentRequest->status) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Yêu cầu này không ở trạng thái chờ duyệt.'
            ]);
            return;
        }

        // Cập nhật trạng thái yêu cầu
        $this->currentRequest->status = StudentUpdateStatus::ClassOfficerApproved;
        $this->currentRequest->save();

        // Lưu thông tin người duyệt
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        ApproveStudentUpdates::create([
            'approveable_type' => Student::class,
            'approveable_id' => $student->id,
            'status' => StudentUpdateStatus::ClassOfficerApproved,
            'student_info_updates_id' => $this->currentRequest->id,
            'note' => $this->note,
        ]);

        // Ghi log hoạt động
        LogActivityHelper::create(
            'Duyệt yêu cầu chỉnh sửa thông tin sinh viên',
            'Lớp trưởng ' . $student->full_name . ' (Mã SV: ' . $student->code . ') đã duyệt yêu cầu chỉnh sửa thông tin của sinh viên ' .
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

        // Kiểm tra xem yêu cầu có đang ở trạng thái chờ duyệt không
        if (StudentUpdateStatus::Pending !== $this->currentRequest->status) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Yêu cầu này không ở trạng thái chờ duyệt.'
            ]);
            return;
        }

        // Cập nhật trạng thái yêu cầu
        $this->currentRequest->status = StudentUpdateStatus::Reject;
        $this->currentRequest->save();

        // Lưu thông tin người từ chối
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        ApproveStudentUpdates::create([
            'approveable_type' => Student::class,
            'approveable_id' => $student->id,
            'status' => StudentUpdateStatus::Reject,
            'student_info_updates_id' => $this->currentRequest->id,
            'note' => $this->note,
        ]);

        // Ghi log hoạt động
        LogActivityHelper::create(
            'Từ chối yêu cầu chỉnh sửa thông tin sinh viên',
            'Lớp trưởng ' . $student->full_name . ' (Mã SV: ' . $student->code . ') đã từ chối yêu cầu chỉnh sửa thông tin của sinh viên ' .
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
