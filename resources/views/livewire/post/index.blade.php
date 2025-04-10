<div>
    <div class="card">
        <div class="py-3 card-header d-flex justify-content-between">
            <div class="gap-2 d-flex">
                <div>
                    <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Tìm kiếm...">
                </div>
            </div>
            <div class="gap-2 d-flex">
                @can('create', \App\Models\Post::class)
                    <div>
                        <a href="{{ route('posts.create') }}" type="button" class="px-2 shadow btn btn-primary btn-icon fw-semibold">
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
                        <th width="25%">Tiêu đề</th>
                        <th width="30%">Nội dung</th>
                        <th width="15%">Người tạo</th>
                        <th width="10%">Trạng thái</th>
                        <th width="15%">Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $item)
                        <tr>
                            <td class="text-center" width="5%">{{ $loop->index + 1 + $posts->perPage() * ($posts->currentPage() - 1) }}</td>
                            <td width="25%">
                                <a href="{{ route('posts.show', $item->id) }}" class="fw-semibold">
                                    {{ $item->title }}
                                </a>
                            </td>
                            <td width="30%">{{ \Illuminate\Support\Str::limit(strip_tags($item->content), 100) }}</td>
                            <td width="15%">{{ $item->user->full_name ?? 'N/A' }}</td>
                            <td width="10%">
                                <x-post-status-badge :status="$item->status" />
                            </td>
                            <td width="15%">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <x-table-empty :colspan="6" />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $posts->links('vendor.pagination.theme') }}
</div>
