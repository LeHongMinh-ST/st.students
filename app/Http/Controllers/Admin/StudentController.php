<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionYear;
use App\Models\Student;
use App\Models\StudentUpdate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class StudentController extends Controller
{
    public function index($admissionYear): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('viewAny', Student::class);

        $admissionYear = AdmissionYear::where('admission_year', $admissionYear)->first();
        if (!$admissionYear) {
            abort(404);
        }

        return view('pages.student.index', compact('admissionYear'));
    }

    public function admissions(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('viewAny', Student::class);
        return view('pages.student.admissions');
    }

    public function import(AdmissionYear $admissionYear): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('create', Student::class);

        return view('pages.student.import', compact('admissionYear'));
    }

    public function show(Student $student): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('view', $student);

        return view('pages.student.show', compact('student'));
    }

    public function edit(Student $student): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('update', $student);

        return view('pages.student.edit', compact('student'));
    }

    public function editDetail(Student $student): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('update', $student);

        return view('pages.student.edit-detail', compact('student'));
    }

    public function requestEdit(Student $student): View|Application|Factory|RedirectResponse
    {
        // Sinh viên chỉ có thể yêu cầu chỉnh sửa thông tin của chính mình
        Gate::authorize('create', [StudentUpdate::class, $student]);

        return view('pages.student.request-edit', compact('student'));
    }

    public function viewUpdateRequest(StudentUpdate $update): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('view', $update);

        return view('pages.student.view-update-request', compact('update'));
    }

    public function classMonitorApprovals(): View|Application|Factory|RedirectResponse
    {
        // Kiểm tra xem người dùng có phải là lớp trưởng không
        Gate::authorize('approveAsClassMonitor', StudentUpdate::class);

        return view('pages.student.class-monitor-approvals');
    }

    public function teacherApprovals(): View|Application|Factory|RedirectResponse
    {
        // Kiểm tra xem người dùng có phải là giáo viên không
        Gate::authorize('approveAsTeacher', StudentUpdate::class);

        return view('pages.student.teacher-approvals');
    }

    public function adminApprovals(): View|Application|Factory|RedirectResponse
    {
        // Kiểm tra xem người dùng có quyền duyệt không
        Gate::authorize('approveAsAdmin', StudentUpdate::class);

        return view('pages.student.admin-approvals');
    }
}
