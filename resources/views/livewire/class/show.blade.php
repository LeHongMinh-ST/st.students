<div>
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
                <div class="col-md-4">
                    <div class="mb-2">
                        <label class="form-label fw-semibold mb-0">Giáo viên chủ nhiệm:</label>
                        <div>{{ $classTeacher['name'] }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-2">
                        <label class="form-label fw-semibold mb-0">Cố vấn học tập:</label>
                        <div>{{ $classSubTeacher['name'] }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-2">
                        <label class="form-label fw-semibold mb-0">Chuyên ngành:</label>
                        <div>{{ $majorName ?? 'Chưa có thông tin' }}</div>
                    </div>
                </div>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="mb-2">
                        <label class="form-label fw-semibold mb-0">Lớp trưởng:</label>
                        <div>{{ $classPresident ? $classPresident->full_name : 'Chưa phân công' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-2">
                        <label class="form-label fw-semibold mb-0">Bí thư:</label>
                        <div>{{ $classSecretary ? $classSecretary->full_name : 'Chưa phân công' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê lớp học -->
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="mb-0"><i class="ph-chart-bar me-1"></i> Thống kê lớp học</h5>
        </div>
        <div class="card-body py-2">
            <div class="row g-2">
                <div class="col-md-2">
                    <div class="card bg-primary text-white text-center p-2">
                        <h4 class="mb-0">{{ $totalStudents }}</h4>
                        <div class="small">Sĩ số ban đầu</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-success text-white text-center p-2">
                        <h4 class="mb-0">{{ $currentlyStudying }}</h4>
                        <div class="small">Đang học</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-info text-white text-center p-2">
                        <h4 class="mb-0">{{ $graduated }}</h4>
                        <div class="small">Đã tốt nghiệp</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-warning text-white text-center p-2">
                        <h4 class="mb-0">{{ $deferred }}</h4>
                        <div class="small">Bảo lưu</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-danger text-white text-center p-2">
                        <h4 class="mb-0">{{ $dropped }}</h4>
                        <div class="small">Đã nghỉ học</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-secondary text-white text-center p-2">
                        <h4 class="mb-0">{{ $warned }}</h4>
                        <div class="small">Cảnh báo</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="ph-student me-1"></i> Danh sách sinh viên</h5>

        </div>
        <div class="card-body">
            <div class="py-3 d-flex justify-content-between">
                <div class="gap-2 d-flex">
                    <div>
                        <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Tìm kiếm...">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light {{ $tab === 'students' ? 'active' : '' }}" wire:click="setTab('students')">Tất cả</button>
                        <button type="button" class="btn btn-light {{ $tab === 'studying' ? 'active' : '' }}" wire:click="setTab('studying')">Sinh viên đang học</button>
                        <button type="button" class="btn btn-light {{ $tab === 'graduated' ? 'active' : '' }}" wire:click="setTab('graduated')">Sinh viên đã tốt nghiệp</button>
                        <button type="button" class="btn btn-light {{ $tab === 'deferred' ? 'active' : '' }}" wire:click="setTab('deferred')">Sinh viên bảo lưu</button>
                        <button type="button" class="btn btn-light {{ $tab === 'dropped' ? 'active' : '' }}" wire:click="setTab('dropped')">Sinh viên đã nghỉ học</button>
                        <button type="button" class="btn btn-light {{ $tab === 'warned' ? 'active' : '' }}" wire:click="setTab('warned')">Sinh viên cảnh báo</button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <div wire:loading class="my-3 text-center w-100">
                    <span class="spinner-border spinner-border-sm"></span> Đang tải dữ liệu...
                </div>
                <table class="table fs-table" wire:loading.remove>
                    <thead>
                        <tr class="table-light">
                            <th width="5%">STT</th>
                            <th width="20%">Họ và tên</th>
                            <th width="10%">Mã sinh viên</th>
                            <th width="15%">Email</th>
                            <th width="10%">Số điện thoại</th>
                            <th width="10%">Vai trò</th>
                            <th width="10%">Khóa</th>
                            <th width="10%">Trạng thái</th>
                            <th width="10%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $item)
                            <tr>
                                <td class="text-center" width="5%">{{ $loop->index + 1 + $students->perPage() * ($students->currentPage() - 1) }}</td>
                                <td width="20%">
                                    <a href="{{ route('students.show', $item->id) }}" class="fw-semibold">
                                        {{ $item->full_name }}
                                    </a>
                                </td>
                                <td width="10%">{{ $item->code }}</td>
                                <td width="15%">{{ $item->email }}</td>
                                <td width="10%">{{ $item->phone }}</td>
                                <td width="10%">
                                    <x-student-role-badge :role="\App\Enums\StudentRole::from($item->pivot->role)" />
                                </td>
                                <td width="10%">{{ $item->admissionYear ? $item->admissionYear->admission_year : 'N/A' }}</td>
                                <td width="10%">
                                    <x-student-status-badge :status="$item->status" />
                                </td>
                                <td width="10%">
                                    <div class="d-inline-flex">
                                        <a href="{{ route('students.show', $item->id) }}" class="text-body" data-bs-popup="tooltip" title="Xem chi tiết">
                                            <i class="ph-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-table-empty :colspan="9" />
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $students->links('vendor.pagination.theme') }}
        </div>
    </div>

    <!-- Lịch sử phân công giáo viên -->
    <livewire:class.teacher-assignment :class="$class" />
</div>
