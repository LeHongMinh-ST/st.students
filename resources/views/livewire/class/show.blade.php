<div>
    <!-- Tab navigation -->
    <div class="card mb-3">
        <div class="card-header">
            <div class="nav nav-tabs nav-tabs-highlight">
                <a href="#" class="nav-item nav-link {{ $tab === 'info' ? 'active' : '' }}" wire:click.prevent="setTab('info')">
                    <i class="ph-info me-1"></i> Thông tin & Quản lý lớp học
                </a>
                <a href="#" class="nav-item nav-link {{ $tab === 'stats' ? 'active' : '' }}" wire:click.prevent="setTab('stats')">
                    <i class="ph-chart-bar me-1"></i> Thống kê & Danh sách sinh viên
                </a>
                <a href="#" class="nav-item nav-link {{ $tab === 'teachers' ? 'active' : '' }}" wire:click.prevent="setTab('teachers')">
                    <i class="ph-users-three me-1"></i> Phân công Giáo viên - Nhân sự
                </a>
            </div>
        </div>
    </div>

    <!-- Tab content -->
    <div class="tab-content">
        <!-- Tab 1: Thông tin lớp học & Quản lý lớp học -->
        <div class="tab-pane fade {{ $tab === 'info' ? 'show active' : '' }}" id="info">
            <!-- Thông tin lớp học -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ph-info me-1"></i> Thông tin lớp học</h5>
                </div>
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-0">Tên lớp:</label>
                                <div>{{ $class->name }}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-0">Mã lớp:</label>
                                <div>{{ $class->code }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-lg-6">
                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-0">Loại lớp:</label>
                                <div>{{ $class->type->label() }}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-0">Trạng thái:</label>
                                <div>
                                    <x-class-status-badge :status="$class->status" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold mb-0">Mô tả:</label>
                        <div>{{ $class->description }}</div>
                    </div>

                    <div class="text-end">
                        @can('update', $class)
                            <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-primary">Chỉnh sửa <i class="ph-pencil ms-2"></i></a>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Thông tin quản lý lớp học -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ph-users me-1"></i> Quản lý lớp học</h5>
                </div>
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-0">Giáo viên chủ nhiệm:</label>
                                <div>{{ $classTeacher['name'] }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-0">Chuyên ngành:</label>
                                <div>{{ $majorName ?? 'Chưa có thông tin' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-0">Lớp trưởng:</label>
                                <div>{{ $classPresident ? $classPresident->full_name . ' (' . $classPresident->code . ')' : 'Chưa phân công' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-0">Lớp phó:</label>
                                <div>{{ $classVicePresident ? $classVicePresident->full_name . ' (' . $classVicePresident->code . ')' : 'Chưa phân công' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-0">Bí thư:</label>
                                <div>{{ $classSecretary ? $classSecretary->full_name . ' (' . $classSecretary->code . ')' : 'Chưa phân công' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-0">Phó bí thư:</label>
                                <div>{{ $classViceSecretary ? $classViceSecretary->full_name . ' (' . $classViceSecretary->code . ')' : 'Chưa phân công' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Thống kê lớp học & Danh sách sinh viên -->
        <div class="tab-pane fade {{ $tab === 'stats' ? 'show active' : '' }}" id="stats">
            <!-- Sử dụng component con cho thống kê lớp học -->
            <livewire:class.class-statistics :class="$class" />

            <!-- Sử dụng component con cho danh sách sinh viên -->
            <livewire:class.student-list :class="$class" />
        </div>

        <!-- Tab 3: Phân công Giáo viên - Nhân sự -->
        <div class="tab-pane fade {{ $tab === 'teachers' ? 'show active' : '' }}" id="teachers">
            <!-- Phần phân công giáo viên -->
            <livewire:class.teacher-assignment :class="$class" />

            <!-- Phần phân công cán sự lớp -->
            <div class="mt-4"></div>
            <livewire:class.student-assignment :class="$class" />
        </div>
    </div>
</div>

@script
<script>
    // Handle tab switching from Livewire
    window.addEventListener('setActiveTab', (event) => {
        const tabName = event.detail;
        // Find the tab link and click it
        document.querySelector(`.nav-link[wire\\:click\.prevent="setTab('${tabName}')"]`).click();
    });
</script>
@endscript
