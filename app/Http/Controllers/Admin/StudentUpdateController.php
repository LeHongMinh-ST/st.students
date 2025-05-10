<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\StudentUpdateStatus;
use App\Helpers\LogActivityHelper;
use App\Http\Controllers\Controller;
use App\Models\ApproveStudentUpdates;
use App\Models\Student;
use App\Models\StudentUpdate;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class StudentUpdateController extends Controller
{
    /**
     * Hiển thị trang quản lý yêu cầu chỉnh sửa thông tin sinh viên
     */
    public function index(): View|Application|Factory|RedirectResponse
    {
        // Kiểm tra quyền xem danh sách yêu cầu
        Gate::authorize('viewAny', StudentUpdate::class);

        return view('pages.student-update.index');
    }

    /**
     * Hiển thị chi tiết yêu cầu chỉnh sửa
     */
    public function show(StudentUpdate $update): View|Application|Factory|RedirectResponse
    {
        // Kiểm tra quyền xem chi tiết yêu cầu
        Gate::authorize('view', $update);

        return view('pages.student-update.show', compact('update'));
    }

    /**
     * Duyệt yêu cầu chỉnh sửa thông tin
     */
    public function approve(Request $request, StudentUpdate $update): RedirectResponse
    {
        $user = Auth::user();
        $note = $request->input('note');

        // Superadmin có thể duyệt yêu cầu ở bất kỳ trạng thái nào
        if ($user->isSuperAdmin()) {
            // Nếu là superadmin, cập nhật trạng thái tiếp theo dựa trên trạng thái hiện tại
            if (StudentUpdateStatus::Pending === $update->status) {
                $newStatus = StudentUpdateStatus::ClassOfficerApproved;
            } elseif (StudentUpdateStatus::ClassOfficerApproved === $update->status) {
                $newStatus = StudentUpdateStatus::TeacherApproved;
            } else {
                $newStatus = StudentUpdateStatus::OfficerApproved;
            }

            // Lưu thông tin người duyệt (superadmin)
            ApproveStudentUpdates::create([
                'approveable_type' => User::class,
                'approveable_id' => $user->id,
                'status' => $newStatus,
                'student_info_updates_id' => $update->id,
                'note' => $note,
            ]);

            // Nếu duyệt đến trạng thái cuối cùng, cập nhật thông tin sinh viên
            if (StudentUpdateStatus::OfficerApproved === $newStatus) {
                $this->updateStudentInfo($update);
            }

            // Ghi log hoạt động
            LogActivityHelper::create(
                'Duyệt yêu cầu chỉnh sửa thông tin sinh viên',
                'Quản trị viên ' . $user->full_name . ' đã duyệt yêu cầu chỉnh sửa thông tin của sinh viên ' .
                $update->student->full_name . ' (Mã SV: ' . $update->student->code . ')'
            );
        }
        // Kiểm tra quyền duyệt dựa trên trạng thái hiện tại
        elseif (StudentUpdateStatus::Pending === $update->status) {
            Gate::authorize('approveAsClassMonitor', $update);
            $newStatus = StudentUpdateStatus::ClassOfficerApproved;

            // Lưu thông tin người duyệt (lớp trưởng)
            $student = Student::where('user_id', $user->id)->first();

            ApproveStudentUpdates::create([
                'approveable_type' => Student::class,
                'approveable_id' => $student->id,
                'status' => $newStatus,
                'student_info_updates_id' => $update->id,
                'note' => $note,
            ]);

            // Ghi log hoạt động
            LogActivityHelper::create(
                'Duyệt yêu cầu chỉnh sửa thông tin sinh viên',
                'Lớp trưởng ' . $student->full_name . ' (Mã SV: ' . $student->code . ') đã duyệt yêu cầu chỉnh sửa thông tin của sinh viên ' .
                $update->student->full_name . ' (Mã SV: ' . $update->student->code . ')'
            );
        } elseif (StudentUpdateStatus::ClassOfficerApproved === $update->status) {
            Gate::authorize('approveAsTeacher', $update);
            $newStatus = StudentUpdateStatus::TeacherApproved;

            // Lưu thông tin người duyệt (giáo viên)
            ApproveStudentUpdates::create([
                'approveable_type' => User::class,
                'approveable_id' => $user->id,
                'status' => $newStatus,
                'student_info_updates_id' => $update->id,
                'note' => $note,
            ]);

            // Ghi log hoạt động
            LogActivityHelper::create(
                'Duyệt yêu cầu chỉnh sửa thông tin sinh viên',
                'Giáo viên ' . $user->full_name . ' đã duyệt yêu cầu chỉnh sửa thông tin của sinh viên ' .
                $update->student->full_name . ' (Mã SV: ' . $update->student->code . ')'
            );
        } elseif (StudentUpdateStatus::TeacherApproved === $update->status) {
            Gate::authorize('approveAsAdmin', $update);
            $newStatus = StudentUpdateStatus::OfficerApproved;

            // Lưu thông tin người duyệt (admin)
            ApproveStudentUpdates::create([
                'approveable_type' => User::class,
                'approveable_id' => $user->id,
                'status' => $newStatus,
                'student_info_updates_id' => $update->id,
                'note' => $note,
            ]);

            // Cập nhật thông tin sinh viên
            $student = $update->student;
            $changes = json_decode($update->change_column, true) ?? [];

            $updateData = [];
            foreach ($changes as $field => $change) {
                if ('thumbnail' !== $field) { // Xử lý riêng cho thumbnail
                    $updateData[$field] = $change['new'];
                }
            }

            // Nếu có thay đổi ảnh đại diện
            if (isset($changes['thumbnail'])) {
                $updateData['thumbnail'] = $update->thumbnail;
            }

            // Cập nhật thông tin sinh viên
            $student->update($updateData);

            // Ghi log hoạt động
            LogActivityHelper::create(
                'Duyệt yêu cầu chỉnh sửa thông tin sinh viên',
                'Quản trị viên ' . $user->full_name . ' đã duyệt yêu cầu chỉnh sửa thông tin của sinh viên ' .
                $update->student->full_name . ' (Mã SV: ' . $update->student->code . ')'
            );
        } else {
            return redirect()->back()->with('error', 'Không thể duyệt yêu cầu này do trạng thái không hợp lệ.');
        }

        // Cập nhật trạng thái yêu cầu
        $update->status = $newStatus;
        $update->save();

        return redirect()->route('student-updates.show', $update->id)->with('success', 'Yêu cầu chỉnh sửa thông tin đã được duyệt thành công.');
    }

    /**
     * Từ chối yêu cầu chỉnh sửa thông tin
     */
    public function reject(Request $request, StudentUpdate $update): RedirectResponse
    {
        $user = Auth::user();
        $note = $request->input('note');

        // Kiểm tra xem có ghi chú từ chối không
        if (empty($note)) {
            return redirect()->back()->with('error', 'Vui lòng nhập lý do từ chối.');
        }

        // Kiểm tra quyền từ chối
        Gate::authorize('reject', $update);

        // Cập nhật trạng thái yêu cầu
        $update->status = StudentUpdateStatus::Reject;
        $update->save();

        // Lưu thông tin người từ chối
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();

            ApproveStudentUpdates::create([
                'approveable_type' => Student::class,
                'approveable_id' => $student->id,
                'status' => StudentUpdateStatus::Reject,
                'student_info_updates_id' => $update->id,
                'note' => $note,
            ]);

            // Ghi log hoạt động
            LogActivityHelper::create(
                'Từ chối yêu cầu chỉnh sửa thông tin sinh viên',
                'Lớp trưởng ' . $student->full_name . ' (Mã SV: ' . $student->code . ') đã từ chối yêu cầu chỉnh sửa thông tin của sinh viên ' .
                $update->student->full_name . ' (Mã SV: ' . $update->student->code . ')'
            );
        } else {
            ApproveStudentUpdates::create([
                'approveable_type' => User::class,
                'approveable_id' => $user->id,
                'status' => StudentUpdateStatus::Reject,
                'student_info_updates_id' => $update->id,
                'note' => $note,
            ]);

            // Ghi log hoạt động
            LogActivityHelper::create(
                'Từ chối yêu cầu chỉnh sửa thông tin sinh viên',
                $user->full_name . ' đã từ chối yêu cầu chỉnh sửa thông tin của sinh viên ' .
                $update->student->full_name . ' (Mã SV: ' . $update->student->code . ')'
            );
        }

        return redirect()->route('student-updates.show', $update->id)->with('success', 'Yêu cầu chỉnh sửa thông tin đã bị từ chối.');
    }

    /**
     * Cập nhật thông tin sinh viên khi yêu cầu được duyệt hoàn tất
     */
    private function updateStudentInfo(StudentUpdate $update): void
    {
        $student = $update->student;
        $changes = json_decode($update->change_column, true) ?? [];

        $updateData = [];
        foreach ($changes as $field => $change) {
            if ('thumbnail' !== $field) { // Xử lý riêng cho thumbnail
                $updateData[$field] = $change['new'];
            }
        }

        // Nếu có thay đổi ảnh đại diện
        if (isset($changes['thumbnail'])) {
            $updateData['thumbnail'] = $update->thumbnail;
        }

        // Cập nhật thông tin sinh viên
        $student->update($updateData);
    }
}
