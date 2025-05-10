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
</div>
