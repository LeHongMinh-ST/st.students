<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StudentRole;
use App\Models\ClassStudent;
use App\Models\Feedback;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FeedbackPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Superadmin hoặc người dùng có quyền xem danh sách phản ánh
        if ($user->isSuperAdmin() || $user->hasPermission('feedback.index')) {
            return true;
        }

        // Giáo viên chủ nhiệm có thể xem danh sách phản ánh của lớp mình chủ nhiệm
        if ($user->hasPermission('class.teacher')) {
            return true;
        }

        // Sinh viên lớp trưởng có thể xem danh sách phản ánh của lớp mình
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                // Kiểm tra xem sinh viên có phải là lớp trưởng không
                $isClassMonitor = ClassStudent::where('student_id', $student->id)
                    ->where('role', StudentRole::President->value)
                    ->exists();

                return $isClassMonitor;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Feedback $feedback): bool
    {
        // Superadmin hoặc người dùng có quyền xem chi tiết phản ánh
        if ($user->isSuperAdmin() || $user->hasPermission('feedback.show')) {
            return true;
        }

        // Giáo viên chủ nhiệm có thể xem chi tiết phản ánh của lớp mình chủ nhiệm
        if ($user->hasPermission('class.teacher')) {
            // Lấy danh sách lớp mà giáo viên là chủ nhiệm
            $teacherClassIds = DB::table('class_assigns')
                ->where('teacher_id', $user->id)
                ->pluck('class_id')
                ->toArray();

            // Kiểm tra xem phản ánh có thuộc lớp nào mà giáo viên là chủ nhiệm không
            return in_array($feedback->class_id, $teacherClassIds);
        }

        // Sinh viên lớp trưởng có thể xem chi tiết phản ánh của lớp mình
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                // Kiểm tra xem sinh viên có phải là lớp trưởng không
                $classIds = ClassStudent::where('student_id', $student->id)
                    ->where('role', StudentRole::President->value)
                    ->pluck('class_id')
                    ->toArray();

                // Kiểm tra xem phản ánh có thuộc lớp nào mà sinh viên là lớp trưởng không
                if (in_array($feedback->class_id, $classIds)) {
                    return true;
                }

                // Sinh viên cũng có thể xem phản ánh do chính mình tạo
                return $feedback->student_id === $student->id;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Chỉ sinh viên lớp trưởng mới có thể tạo phản ánh
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                // Kiểm tra xem sinh viên có phải là lớp trưởng không
                $isClassMonitor = ClassStudent::where('student_id', $student->id)
                    ->where('role', StudentRole::President->value)
                    ->exists();

                return $isClassMonitor;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Feedback $feedback): bool
    {
        // Superadmin hoặc người dùng có quyền sửa phản ánh
        if ($user->isSuperAdmin() || $user->hasPermission('feedback.edit')) {
            return true;
        }

        // Sinh viên lớp trưởng có thể sửa phản ánh do chính mình tạo
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                // Kiểm tra xem sinh viên có phải là lớp trưởng không
                $isClassMonitor = ClassStudent::where('student_id', $student->id)
                    ->where('role', StudentRole::President->value)
                    ->exists();

                // Chỉ cho phép sửa phản ánh do chính mình tạo
                return $isClassMonitor && $feedback->student_id === $student->id;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Feedback $feedback): bool
    {
        // Superadmin hoặc người dùng có quyền xóa phản ánh
        if ($user->isSuperAdmin() || $user->hasPermission('feedback.delete')) {
            return true;
        }

        // Sinh viên lớp trưởng có thể xóa phản ánh do chính mình tạo
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                // Kiểm tra xem sinh viên có phải là lớp trưởng không
                $isClassMonitor = ClassStudent::where('student_id', $student->id)
                    ->where('role', StudentRole::President->value)
                    ->exists();

                // Chỉ cho phép xóa phản ánh do chính mình tạo
                return $isClassMonitor && $feedback->student_id === $student->id;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Feedback $feedback): bool
    {
        // Chỉ superadmin hoặc người dùng có quyền xóa phản ánh mới có thể khôi phục
        return $user->isSuperAdmin() || $user->hasPermission('feedback.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Feedback $feedback): bool
    {
        // Chỉ superadmin hoặc người dùng có quyền xóa phản ánh mới có thể xóa vĩnh viễn
        return $user->isSuperAdmin() || $user->hasPermission('feedback.delete');
    }

    /**
     * Determine whether the user can reply to the feedback.
     */
    public function reply(User $user, Feedback $feedback): bool
    {
        // Superadmin hoặc người dùng có quyền phản hồi phản ánh
        if ($user->isSuperAdmin() || $user->hasPermission('feedback.reply')) {
            return true;
        }

        // Giáo viên chủ nhiệm có thể phản hồi phản ánh của lớp mình chủ nhiệm
        if ($user->hasPermission('class.teacher')) {
            // Lấy danh sách lớp mà giáo viên là chủ nhiệm
            $teacherClassIds = DB::table('class_assigns')
                ->where('teacher_id', $user->id)
                ->pluck('class_id')
                ->toArray();

            // Kiểm tra xem phản ánh có thuộc lớp nào mà giáo viên là chủ nhiệm không
            return in_array($feedback->class_id, $teacherClassIds);
        }

        return false;
    }

    /**
     * Determine whether the user can update the status of the feedback.
     */
    public function updateStatus(User $user, Feedback $feedback): bool
    {
        // Superadmin hoặc người dùng có quyền cập nhật trạng thái phản ánh
        if ($user->isSuperAdmin() || $user->hasPermission('feedback.update-status')) {
            return true;
        }

        // Giáo viên chủ nhiệm có thể cập nhật trạng thái phản ánh của lớp mình chủ nhiệm
        if ($user->hasPermission('class.teacher')) {
            // Lấy danh sách lớp mà giáo viên là chủ nhiệm
            $teacherClassIds = DB::table('class_assigns')
                ->where('teacher_id', $user->id)
                ->pluck('class_id')
                ->toArray();

            // Kiểm tra xem phản ánh có thuộc lớp nào mà giáo viên là chủ nhiệm không
            return in_array($feedback->class_id, $teacherClassIds);
        }

        return false;
    }
}
