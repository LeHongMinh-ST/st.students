<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Thông tin đợt cảnh báo</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên đợt cảnh báo:</label>
                        <div>{{ $warning->name }}</div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Học kỳ:</label>
                        <div>{{ $warning->semester->name ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                @can('update', $warning)
                    <a href="{{ route('warnings.edit', $warning->id) }}" class="btn btn-primary">Chỉnh sửa <i class="ph-pencil ms-2"></i></a>
                @endcan
                @can('import', $warning)
                    <a href="{{ route('warnings.import', $warning->id) }}" class="btn btn-success">Import sinh viên <i class="ph-upload-simple ms-2"></i></a>
                @endcan
            </div>
        </div>
    </div>
    
    <!-- Thống kê sinh viên cảnh báo -->
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="mb-0">Thống kê sinh viên cảnh báo</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-primary text-white text-center p-3">
                        <h3>{{ $warning->total_students }}</h3>
                        <div>Tổng số sinh viên</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white text-center p-3">
                        <h3>{{ $warning->level1_students }}</h3>
                        <div>Cảnh báo mức 1</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white text-center p-3">
                        <h3>{{ $warning->level2_students }}</h3>
                        <div>Cảnh báo mức 2</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách sinh viên cảnh báo -->
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="mb-0">Danh sách sinh viên cảnh báo</h5>
        </div>
        <div class="card-body">
            <div class="py-3 d-flex justify-content-between">
                <div class="gap-2 d-flex">
                    <div>
                        <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Tìm kiếm...">
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
                            <th width="15%">Mã sinh viên</th>
                            <th width="20%">Lý do</th>
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
                                <td width="15%">{{ $item->code }}</td>
                                
                                <td width="20%">{{ $item->pivot->note ?: 'N/A' }}</td>
                                <td width="10%">
                                    <div class="d-inline-flex">
                                        <a href="{{ route('students.show', $item->id) }}" class="text-body" data-bs-popup="tooltip" title="Xem chi tiết">
                                            <i class="ph-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Không có dữ liệu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $students->links('vendor.pagination.theme') }}
        </div>
    </div>
</div>
