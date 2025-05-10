<?php

declare(strict_types=1);

namespace App\Livewire\StudentUpdate;

use App\Enums\StudentRole;
use App\Models\ClassStudent;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public string $activeTab = 'all';
    public bool $isClassMonitor = false;
    public bool $isTeacher = false;
    public bool $isAdmin = false;

    public function mount(): void
    {
        $user = Auth::user();

        // Kiểm tra xem người dùng có phải là lớp trưởng không
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                $this->isClassMonitor = ClassStudent::where('student_id', $student->id)
                    ->where('role', StudentRole::President->value)
                    ->exists();

                // Nếu là lớp trưởng, mặc định tab là yêu cầu của lớp
                if ($this->isClassMonitor) {
                    $this->activeTab = 'class-monitor';
                }
            }
        }

        // Kiểm tra xem người dùng có phải là giáo viên không
        $this->isTeacher = $user->hasPermission('class.teacher');
        if ($this->isTeacher && !$this->isClassMonitor) {
            $this->activeTab = 'teacher';
        }

        // Kiểm tra xem người dùng có quyền admin không
        $this->isAdmin = $user->hasPermission('student.update.approve');
        if ($this->isAdmin && !$this->isTeacher && !$this->isClassMonitor) {
            $this->activeTab = 'admin';
        }
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.student-update.index');
    }
}
