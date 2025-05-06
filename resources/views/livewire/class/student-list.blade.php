<div>
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
                        <button type="button" class="btn btn-light {{ $filter === 'all' ? 'active' : '' }}" wire:click="setFilter('all')">Tất cả</button>
                        <button type="button" class="btn btn-light {{ $filter === 'studying' ? 'active' : '' }}" wire:click="setFilter('studying')">Sinh viên đang học</button>
                        <button type="button" class="btn btn-light {{ $filter === 'graduated' ? 'active' : '' }}" wire:click="setFilter('graduated')">Sinh viên đã tốt nghiệp</button>
                        <button type="button" class="btn btn-light {{ $filter === 'deferred' ? 'active' : '' }}" wire:click="setFilter('deferred')">Sinh viên bảo lưu</button>
                        <button type="button" class="btn btn-light {{ $filter === 'dropped' ? 'active' : '' }}" wire:click="setFilter('dropped')">Sinh viên đã nghỉ học</button>
                        <button type="button" class="btn btn-light {{ $filter === 'warned' ? 'active' : '' }}" wire:click="setFilter('warned')">Sinh viên cảnh báo</button>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="card mt-3">
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
    </div>
    {{ $students->links('vendor.pagination.theme') }}

</div>
