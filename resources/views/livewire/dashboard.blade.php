<div>
    <!-- Statistic Cards -->
    <div class="row">
        @if($canViewTotalStudents)
        <!-- Total Students - Cool Blue -->
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('students.index') }}" class="text-white">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 text-white">{{ number_format($totalStudents) }}</h3>
                            <div class="ms-auto">
                                <i class="ph-users-three ph-2x me-1"></i>
                            </div>
                        </div>

                        <div class="text-white">
                            Tổng số sinh viên
                            <div class="fs-sm opacity-75"> Tất cả sinh viên trong hệ thống</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        @if($canViewGraduatedStudents)
        <!-- Graduated Students - Teal -->
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('graduation.index') }}" class="text-white">
                <div class="card bg-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 text-white">{{ number_format($graduatedStudents) }}</h3>
                            <div class="ms-auto">

                                <i class="ph-graduation-cap ph-2x me-1"></i>

                            </div>
                        </div>

                        <div class="text-white">
                            Sinh viên tốt nghiệp
                            <div class="fs-sm opacity-75"> Đã hoàn thành chương trình học</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        @if($canViewWarnedStudents)
        <!-- Warned Students - Orange -->
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('warnings.index') }}" class="text-white">
                <div class="card bg-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 text-white">{{ number_format($warnedStudents) }}</h3>
                            <div class="ms-auto">
                                <i class="ph-warning ph-2x me-1"></i>
                            </div>
                        </div>

                        <div class="text-white">
                            Sinh viên cảnh báo
                            <div class="fs-sm opacity-75"> Cảnh báo học tập trong năm qua</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        @if($canViewTotalClasses)
        <!-- Total Classes - Warm Red -->
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('classes.index') }}" class="text-white">

                <div class="card bg-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 text-white">{{ number_format($totalClasses) }}</h3>
                            <div class="ms-auto">
                                <i class="ph-chalkboard-teacher ph-2x me-1"></i>
                            </div>
                        </div>

                        <div class="text-white">
                            Tổng số lớp học
                            <div class="fs-sm opacity-75"> Lớp học đang hoạt động</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        @if(!$canViewTotalStudents && !$canViewGraduatedStudents && !$canViewWarnedStudents && !$canViewTotalClasses)
        <div class="col-12">
            <div class="alert alert-info">
                <i class="ph-info me-2"></i> Bạn không có quyền xem bất kỳ thống kê nào trên bảng điều khiển. Vui lòng liên hệ quản trị viên để được cấp quyền.
            </div>
        </div>
        @endif
    </div>

    <!-- Activity and Approval Tables -->
    <div class="row mt-4">
        <!-- Recent Activities -->
        @if($canViewRecentActivities)
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">Hoạt động mới nhất</h5>
                    <div>
                        <a href="{{ route('activities.index') }}" class="btn btn-link">
                            <i class="ph-arrow-right me-1"></i>Xem tất cả
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <div wire:loading class="my-3 text-center w-100">
                        <span class="spinner-border spinner-border-sm"></span> Đang tải dữ liệu...
                    </div>
                    <table class="table fs-table" wire:loading.remove>
                        <thead>
                            <tr class="table-light">
                                <th width="30%">Người dùng</th>
                                <th width="45%">Hành động</th>
                                <th width="25%">Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $activity)
                            <tr>
                                <td width="30%" class="fw-semibold">{{ $activity['user_name'] }}</td>
                                <td width="45%">{{ $activity['action'] }}</td>
                                <td width="25%">{{ \Carbon\Carbon::parse($activity['created_at'])->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    <img src="{{ asset('assets/images/empty.png') }}" width="150px" alt="empty">
                                    <div class="text-center mb-2">Không có hoạt động nào!</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Pending Approval Requests -->
        @if($canViewPendingUpdates)
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">Yêu cầu duyệt thông tin</h5>
                    <div>
                        <a href="{{ route('student-updates.index') }}" class="btn btn-link">
                            <i class="ph-arrow-right me-1"></i>Xem tất cả
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <div wire:loading class="my-3 text-center w-100">
                        <span class="spinner-border spinner-border-sm"></span> Đang tải dữ liệu...
                    </div>
                    <table class="table fs-table" wire:loading.remove>
                        <thead>
                            <tr class="table-light">
                                <th width="40%">Sinh viên</th>
                                <th width="30%">Trạng thái</th>
                                <th width="30%">Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingUpdates as $update)
                            <tr>
                                <td width="40%">
                                    <a href="{{ route('student-updates.show', $update['id']) }}" class="fw-semibold">
                                        {{ $update['student']['full_name'] ?? 'N/A' }}
                                        <span class="text-muted">({{ $update['student']['code'] ?? 'N/A' }})</span>
                                    </a>
                                </td>
                                <td width="30%">
                                    @php
                                        $status = \App\Enums\StudentUpdateStatus::from($update['status']);
                                        $badgeClass = match($status) {
                                            \App\Enums\StudentUpdateStatus::Pending => 'bg-warning',
                                            \App\Enums\StudentUpdateStatus::ClassOfficerApproved => 'bg-info',
                                            \App\Enums\StudentUpdateStatus::TeacherApproved => 'bg-primary',
                                            \App\Enums\StudentUpdateStatus::OfficerApproved => 'bg-success',
                                            \App\Enums\StudentUpdateStatus::Reject => 'bg-danger',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $status->label() }}</span>
                                </td>
                                <td width="30%">{{ \Carbon\Carbon::parse($update['created_at'])->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    <img src="{{ asset('assets/images/empty.png') }}" width="150px" alt="empty">
                                    <div class="text-center mb-2">Không có yêu cầu nào!</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
