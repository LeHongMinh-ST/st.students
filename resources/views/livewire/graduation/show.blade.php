<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Thông tin đợt tốt nghiệp</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên đợt tốt nghiệp:</label>
                        <div>{{ $ceremony->name }}</div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Năm học:</label>
                        <div>{{ $ceremony->school_year }}</div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Số quyết định:</label>
                        <div>{{ $ceremony->certification }}</div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ngày quyết định:</label>
                        <div>{{ $ceremony->certification_date->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                @can('update', $ceremony)
                    <a href="{{ route('graduation.edit', $ceremony->id) }}" class="btn btn-primary">Chỉnh sửa <i class="ph-pencil ms-2"></i></a>
                @endcan
                @can('import', $ceremony)
                    <a href="{{ route('graduation.import', $ceremony->id) }}" class="btn btn-success">Import sinh viên <i class="ph-upload-simple ms-2"></i></a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Thống kê sinh viên tốt nghiệp -->
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="mb-0">Thống kê sinh viên tốt nghiệp</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-primary text-white text-center p-3">
                        <h3>{{ $ceremony->total_students }}</h3>
                        <div>Tổng số sinh viên</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white text-center p-3">
                        <h3>{{ $ceremony->excellent_students }}</h3>
                        <div>Xuất sắc</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white text-center p-3">
                        <h3>{{ $ceremony->very_good_students }}</h3>
                        <div>Giỏi</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white text-center p-3">
                        <h3>{{ $ceremony->good_students + $ceremony->average_students }}</h3>
                        <div>Khá & Trung bình</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách sinh viên tốt nghiệp -->
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="mb-0">Danh sách sinh viên tốt nghiệp</h5>
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
                            <th width="25%">Email</th>
                            <th width="15%">Điểm TB</th>
                            <th width="20%">Xếp loại</th>
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
                                <td width="25%">{{ $item->pivot->email ?: $item->email }}</td>
                                <td width="15%">{{ number_format($item->pivot->gpa, 2) }}</td>
                                <td width="20%">
                                    <x-rank-graduate-badge :rank="$item->pivot->rank" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Không có dữ liệu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $students->links('vendor.pagination.theme') }}
        </div>
    </div>
</div>
