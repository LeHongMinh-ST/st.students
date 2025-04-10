<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Thông tin lớp học</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên lớp:</label>
                        <div>{{ $class->name }}</div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mã lớp:</label>
                        <div>{{ $class->code }}</div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Loại lớp:</label>
                        <div>{{ $class->type->label() }}</div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Trạng thái:</label>
                        <div>
                            <x-class-status-badge :status="$class->status" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Mô tả:</label>
                <div>{{ $class->description }}</div>
            </div>

            <div class="text-end">
                @can('update', $class)
                    <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-primary">Chỉnh sửa <i class="ph-pencil ms-2"></i></a>
                @endcan
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h5 class="mb-0">Danh sách sinh viên</h5>
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
                            <th width="25%">Họ và tên</th>
                            <th width="15%">Mã sinh viên</th>
                            <th width="15%">Email</th>
                            <th width="15%">Số điện thoại</th>
                            <th width="15%">Vai trò</th>
                            <th width="10%">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $item)
                            <tr>
                                <td class="text-center" width="5%">{{ $loop->index + 1 + $students->perPage() * ($students->currentPage() - 1) }}</td>
                                <td width="25%">
                                    <a href="{{ route('students.show', $item->id) }}" class="fw-semibold">
                                        {{ $item->full_name }}
                                    </a>
                                </td>
                                <td width="15%">{{ $item->code }}</td>
                                <td width="15%">{{ $item->email }}</td>
                                <td width="15%">{{ $item->phone }}</td>
                                <td width="15%">{{ $item->pivot->role->value }}</td>
                                <td width="10%">
                                    <x-student-status-badge :status="$item->status" />
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
