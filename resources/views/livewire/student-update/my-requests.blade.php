<div>
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="ph-magnifying-glass"></i></span>
                    <input type="text" class="form-control" placeholder="Tìm kiếm..." wire:model.live.debounce.300ms="search">
                </div>
            </div>
            <div class="col-md-6">
                <select class="form-select" wire:model.live="status">
                    <option value="">Tất cả trạng thái</option>
                    @foreach (\App\Enums\StudentUpdateStatus::cases() as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr class="table-light">
                    <th width="5%">STT</th>
                    <th width="20%">Ngày yêu cầu</th>
                    <th width="15%">Trạng thái</th>
                    <th width="10%">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $index => $request)
                    <tr>
                        <td class="text-center">{{ $index + 1 + ($requests->currentPage() - 1) * $requests->perPage() }}</td>
                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge {{ $request->status->badgeColor() }}">{{ $request->status->label() }}</span>
                        </td>
                        <td>
                            <a href="{{ route('student-updates.show', $request->id) }}" class="btn btn-sm btn-info">
                                <i class="ph-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Bạn chưa có yêu cầu chỉnh sửa thông tin nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        {{ $requests->links('vendor.pagination.theme') }}
    </div>
</div>
