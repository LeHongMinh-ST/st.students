<div>
    <div class="card">
        <div class="py-3 card-header d-flex justify-content-between">
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
                        <th width="20%">Tên lớp</th>
                        <th width="15%">Mã lớp</th>
                        <th width="20%">Giáo viên chủ nhiệm</th>
                        <th width="10%">Năm học</th>
                        <th width="10%">Trạng thái</th>
                        <th width="10%">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $item)
                        <tr>
                            <td class="text-center" width="5%">{{ $loop->index + 1 + $classes->perPage() * ($classes->currentPage() - 1) }}</td>
                            <td width="20%">
                                <a href="{{ route('classes.show', $item->id) }}" class="fw-semibold">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td width="15%">{{ $item->code }}</td>
                            <td width="20%">
                                {{ auth()->user()->full_name ?? auth()->user()->name }}
                            </td>
                            <td width="10%">
                                <!-- Placeholder for school year -->
                                <span class="text-muted">-</span>
                            </td>
                            <td width="10%">
                                <x-class-status-badge :status="$item->status" />
                            </td>
                            <td width="10%">
                                <a href="{{ route('classes.show', $item->id) }}" class="btn btn-sm btn-info">Chi tiết</a>
                            </td>
                        </tr>
                    @empty
                        <x-table-empty :colspan="7" />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $classes->links('vendor.pagination.theme') }}
</div>
