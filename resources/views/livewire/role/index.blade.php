<div>
    <div class="card">
        <div class="py-3 card-header d-flex justify-content-between">
            <div class="gap-2 d-flex">
                <div>
                    <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Tìm kiếm...">
                </div>
            </div>
            <div class="gap-2 d-flex">
                @can('create', \App\Models\Role::class)
                    <div>
                        <a href="{{ route('roles.create') }}" type="button" class="px-2 shadow btn btn-primary btn-icon fw-semibold">
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
                        <th width="40%">Tên vai trò</th>
                        <th width="40%">Mô tả</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $item)
                        <tr>
                            <td class="text-center" width="5%">{{ $loop->index + 1 + $roles->perPage() * ($roles->currentPage() - 1) }}</td>
                            <td width="40%">
                                <a href="{{ route('roles.edit', $item->id) }}" class="fw-semibold">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td width="40%">{{ $item->description }}</td>

                            <td width="10%">{{ $item->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <x-table-empty :colspan="4" />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $roles->links('vendor.pagination.theme') }}
</div>
