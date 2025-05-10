<div>
    <div class="card">
        <div class="py-3 card-header d-flex justify-content-between">
            <div class="gap-2 d-flex">
                <div>
                    <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Tìm kiếm...">
                </div>
                <div>
                    <select class="form-select" wire:model.live="status">
                        <option value="">Tất cả trạng thái</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
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
                        <th width="15%">Ngày tạo</th>
                        <th width="20%">Tiêu đề</th>
                        <th width="15%">Lớp</th>
                        <th width="15%">Người tạo</th>
                        <th width="10%">Trạng thái</th>
                        <th width="20%">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $index => $feedback)
                        <tr>
                            <td class="text-center" width="5%">{{ $index + 1 + ($feedbacks->currentPage() - 1) * $feedbacks->perPage() }}</td>
                            <td width="15%">{{ $feedback->created_at->format('d/m/Y H:i') }}</td>
                            <td width="20%">
                                <a href="{{ route('feedbacks.show', $feedback->id) }}" class="fw-semibold">
                                    {{ $feedback->title }}
                                </a>
                            </td>
                            <td width="15%">{{ $feedback->class->name ?? 'N/A' }}</td>
                            <td width="15%">{{ $feedback->student->full_name ?? 'N/A' }}</td>
                            <td width="10%">
                                <span class="badge {{ $feedback->status->badgeColor() }}">{{ $feedback->status->label() }}</span>
                            </td>
                            <td width="20%">
                                <a href="{{ route('feedbacks.show', $feedback->id) }}" class="btn btn-sm btn-info">
                                    <i class="ph-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                    @empty
                        <x-table-empty :colspan="7" />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $feedbacks->links('vendor.pagination.theme') }}
    </div>
</div>