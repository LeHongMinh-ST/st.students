<div>
    <div class="card">
        <div class="py-3 card-header d-flex justify-content-between">
            <div class="gap-2 d-flex">
                <div>
                    <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Tìm kiếm...">
                </div>
            </div>
            <div class="gap-2 d-flex">
                @can('create', \App\Models\ClassGenerate::class)
                    <div>
                        <a href="{{ route('classes.create') }}" type="button" class="px-2 shadow btn btn-primary btn-icon fw-semibold">
                            <i class="px-1 ph-plus-circle fw-semibold"></i><span>Thêm mới</span>
                        </a>
                    </div>
                @endcan
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
                        <th width="30%">Mô tả</th>
                        <th width="10%">Loại lớp</th>
                        <th width="10%">Trạng thái</th>
                        <th width="10%">Ngày tạo</th>
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
                            <td width="30%">{{ $item->description }}</td>
                            <td width="10%">{{ $item->type->value }}</td>
                            <td width="10%">
                                @if($item->status->value === 'active')
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-danger">Không hoạt động</span>
                                @endif
                            </td>
                            <td width="10%">{{ $item->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $classes->links('vendor.pagination.theme') }}
</div>
