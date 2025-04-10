<div>
    <!-- Statistic Cards -->
    <div class="row">
        <!-- Total Students - Cool Blue -->
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('students.index') }}" class="text-white">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 text-white">{{ number_format($totalStudents) }}</h3>
                            <div class="ms-auto">
                                <i class="ph-users-three me-1"></i>
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

        <!-- Graduated Students - Teal -->
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('graduation.index') }}" class="text-white">
                <div class="card bg-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 text-white">{{ number_format($graduatedStudents) }}</h3>
                            <div class="ms-auto">

                                <i class="ph-graduation-cap me-1"></i>

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

        <!-- Warned Students - Orange -->
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('warnings.index') }}" class="text-white">
                <div class="card bg-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 text-white">{{ number_format($warnedStudents) }}</h3>
                            <div class="ms-auto">

                                <i class="ph-warning me-1"></i>
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

        <!-- Total Classes - Warm Red -->
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('classes.index') }}" class="text-white">

                <div class="card bg-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 text-white">{{ number_format($totalClasses) }}</h3>
                            <div class="ms-auto">
                                <i class="ph-chalkboard-teacher me-1"></i>
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
    </div>
</div>
