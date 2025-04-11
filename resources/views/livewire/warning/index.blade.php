<div>
    <div class="card">
        <div class="py-3 card-header d-flex justify-content-between">
            <div class="gap-2 d-flex">
                <div>
                    <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Tìm kiếm...">
                </div>
            </div>
            <div class="gap-2 d-flex">
                @can('create', \App\Models\Warning::class)
                    <div>
                        <a href="{{ route('warnings.create') }}" type="button" class="px-2 shadow btn btn-primary btn-icon fw-semibold">
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
                        <th width="25%">Tên đợt cảnh báo</th>
                        <th width="15%">Học kỳ</th>
                        <th width="15%">Năm học</th>
                        <th width="15%">Số quyết định</th>
                        <th width="15%">Số sinh viên</th>
                        <th width="10%">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warnings as $item)
                        <tr>
                            <td class="text-center" width="5%">{{ $loop->index + 1 + $warnings->perPage() * ($warnings->currentPage() - 1) }}</td>
                            <td width="25%">
                                <a href="{{ route('warnings.show', $item->id) }}" class="fw-semibold">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td width="15%">{{ $item->semester->name ?? 'N/A' }}</td>
                            <td width="15%">{{ $item->school_year }}</td>
                            <td width="15%">{{ $item->decision_number }}</td>
                            <td width="15%">{{ $item->total_students }}</td>
                            <td width="10%">
                                <div class="d-inline-flex">
                                    <a href="{{ route('warnings.show', $item->id) }}" class="text-body" data-bs-popup="tooltip" title="Xem chi tiết">
                                        <i class="ph-eye"></i>
                                    </a>
                                    @can('update', $item)
                                        <a href="{{ route('warnings.edit', $item->id) }}" class="text-body mx-2" data-bs-popup="tooltip" title="Chỉnh sửa">
                                            <i class="ph-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('import', $item)
                                        <a href="{{ route('warnings.import', $item->id) }}" class="text-body" data-bs-popup="tooltip" title="Import sinh viên">
                                            <i class="ph-upload-simple"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-table-empty :colspan="7" />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $warnings->links('vendor.pagination.theme') }}
</div>
