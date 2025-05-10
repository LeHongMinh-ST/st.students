<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StudentRole;
use App\Enums\StudentUpdateStatus;
use App\Models\ClassStudent;
use App\Models\Student;
use App\Models\StudentUpdate;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StudentUpdatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Nếu là sinh viên, cho phép xem danh sách yêu cầu của mình
        if ($user->isStudent()) {
            return true;
        }

        // Nếu là lớp trưởng, cho phép xem danh sách yêu cầu của lớp
        if ($this->isClassMonitor($user)) {
            return true;
        }

        // Nếu là giáo viên, cho phép xem danh sách yêu cầu của lớp
        if ($this->isTeacher($user)) {
            return true;
        }

        // Nếu có quyền xem danh sách yêu cầu
        return $user->hasPermission('student.update.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ?StudentUpdate $studentUpdate = null): bool
    {
        // Khi kiểm tra quyền chung, cho phép nếu người dùng có quyền xem bất kỳ yêu cầu nào
        if (null === $studentUpdate) {
            return $user->isStudent() || $this->isClassMonitor($user) || $this->isTeacher($user) || $user->hasPermission('student.update.show');
        }

        // Student can view their own update requests
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if ($student && $student->id === $studentUpdate->student_id) {
                return true;
            }
        }

        // Class monitor can view update requests from their class
        if ($this->isClassMonitor($user)) {
            $classIds = $this->getClassIdsWhereUserIsMonitor($user);
            $studentIds = DB::table('class_students')
                ->whereIn('class_id', $classIds)
                ->pluck('student_id')
                ->toArray();

            if (in_array($studentUpdate->student_id, $studentIds)) {
                return true;
            }
        }

        // Teacher can view update requests from their assigned classes
        if ($this->isTeacher($user)) {
            $classIds = $this->getClassIdsWhereUserIsTeacher($user);
            $studentIds = DB::table('class_students')
                ->whereIn('class_id', $classIds)
                ->pluck('student_id')
                ->toArray();

            if (in_array($studentUpdate->student_id, $studentIds)) {
                return true;
            }
        }

        // Admin or user with permission can view any update request
        return $user->hasPermission('student.update.show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ?Student $targetStudent = null): bool
    {
        // Only students can create update requests
        if (!$user->isStudent()) {
            return false;
        }

        // Check if student has any pending update requests
        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            return false;
        }

        // Nếu có targetStudent, kiểm tra xem người dùng có phải là chủ sở hữu của hồ sơ không
        if (null !== $targetStudent && $student->id !== $targetStudent->id) {
            return false;
        }

        $pendingUpdates = StudentUpdate::where('student_id', $student->id)
            ->whereIn('status', [
                StudentUpdateStatus::Pending->value,
                StudentUpdateStatus::ClassOfficerApproved->value,
                StudentUpdateStatus::TeacherApproved->value,
            ])
            ->count();

        // Student can only create a new update request if they don't have any pending ones
        return 0 === $pendingUpdates;
    }

    /**
     * Determine whether the user can approve as class monitor.
     */
    public function approveAsClassMonitor(User $user, ?StudentUpdate $studentUpdate = null): bool
    {
        // Khi kiểm tra quyền chung, chỉ cần kiểm tra xem người dùng có phải là lớp trưởng không
        if (null === $studentUpdate) {
            return $this->isClassMonitor($user);
        }

        if (!$this->isClassMonitor($user)) {
            return false;
        }

        // Class monitor can only approve pending requests
        if (StudentUpdateStatus::Pending !== $studentUpdate->status) {
            return false;
        }

        $classIds = $this->getClassIdsWhereUserIsMonitor($user);
        $studentClassIds = DB::table('class_students')
            ->where('student_id', $studentUpdate->student_id)
            ->pluck('class_id')
            ->toArray();

        // Check if the student belongs to any of the classes where the user is a monitor
        return count(array_intersect($classIds, $studentClassIds)) > 0;
    }

    /**
     * Determine whether the user can approve as teacher.
     */
    public function approveAsTeacher(User $user, ?StudentUpdate $studentUpdate = null): bool
    {
        // Khi kiểm tra quyền chung, chỉ cần kiểm tra xem người dùng có phải là giáo viên không
        if (null === $studentUpdate) {
            return $this->isTeacher($user);
        }

        if (!$this->isTeacher($user)) {
            return false;
        }

        // Teacher can only approve requests that have been approved by class monitor
        if (StudentUpdateStatus::ClassOfficerApproved !== $studentUpdate->status) {
            return false;
        }

        $classIds = $this->getClassIdsWhereUserIsTeacher($user);
        $studentClassIds = DB::table('class_students')
            ->where('student_id', $studentUpdate->student_id)
            ->pluck('class_id')
            ->toArray();

        // Check if the student belongs to any of the classes where the user is a teacher
        return count(array_intersect($classIds, $studentClassIds)) > 0;
    }

    /**
     * Determine whether the user can approve as admin.
     */
    public function approveAsAdmin(User $user, ?StudentUpdate $studentUpdate = null): bool
    {
        // Khi kiểm tra quyền chung, chỉ cần kiểm tra xem người dùng có quyền duyệt không
        if (null === $studentUpdate) {
            return $user->hasPermission('student.update.approve');
        }

        // Superadmin có thể duyệt yêu cầu ở bất kỳ trạng thái nào
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin thường chỉ có thể duyệt yêu cầu đã được giáo viên duyệt
        if (StudentUpdateStatus::TeacherApproved !== $studentUpdate->status) {
            return false;
        }

        return $user->hasPermission('student.update.approve');
    }

    /**
     * Determine whether the user can reject the update request.
     */
    public function reject(User $user, ?StudentUpdate $studentUpdate = null): bool
    {
        // Khi kiểm tra quyền chung, cho phép nếu người dùng có bất kỳ quyền nào để từ chối
        if (null === $studentUpdate) {
            return $this->isClassMonitor($user) || $this->isTeacher($user) || $user->hasPermission('student.update.approve');
        }

        // Superadmin có thể từ chối yêu cầu ở bất kỳ trạng thái nào
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Class monitor can reject pending requests
        if ($this->isClassMonitor($user) && StudentUpdateStatus::Pending === $studentUpdate->status) {
            $classIds = $this->getClassIdsWhereUserIsMonitor($user);
            $studentClassIds = DB::table('class_students')
                ->where('student_id', $studentUpdate->student_id)
                ->pluck('class_id')
                ->toArray();

            if (count(array_intersect($classIds, $studentClassIds)) > 0) {
                return true;
            }
        }

        // Teacher can reject requests approved by class monitor
        if ($this->isTeacher($user) && StudentUpdateStatus::ClassOfficerApproved === $studentUpdate->status) {
            $classIds = $this->getClassIdsWhereUserIsTeacher($user);
            $studentClassIds = DB::table('class_students')
                ->where('student_id', $studentUpdate->student_id)
                ->pluck('class_id')
                ->toArray();

            if (count(array_intersect($classIds, $studentClassIds)) > 0) {
                return true;
            }
        }

        // Admin can reject requests approved by teacher
        if (StudentUpdateStatus::TeacherApproved === $studentUpdate->status) {
            return $user->hasPermission('student.update.approve');
        }

        return false;
    }

    /**
     * Check if user is a class monitor.
     */
    private function isClassMonitor(User $user): bool
    {
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student) {
                return false;
            }

            return ClassStudent::where('student_id', $student->id)
                ->where('role', StudentRole::President->value)
                ->exists();
        }

        return false;
    }

    /**
     * Get class IDs where user is a monitor.
     */
    private function getClassIdsWhereUserIsMonitor(User $user): array
    {
        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            return [];
        }

        return ClassStudent::where('student_id', $student->id)
            ->where('role', StudentRole::President->value)
            ->pluck('class_id')
            ->toArray();
    }

    /**
     * Check if user is a teacher.
     */
    private function isTeacher(User $user): bool
    {
        return $user->hasPermission('class.teacher');
    }

    /**
     * Get class IDs where user is a teacher.
     */
    private function getClassIdsWhereUserIsTeacher(User $user): array
    {
        return DB::table('class_assigns')
            ->where('teacher_id', $user->id)
            ->pluck('class_id')
            ->toArray();
    }
}
