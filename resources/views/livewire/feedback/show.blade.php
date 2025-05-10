<div>
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Chi tiết phản ánh</h5>
            <div>
                @can('update', $feedback)
                <a href="{{ route('feedbacks.edit', $feedback->id) }}" class="btn btn-warning">
                    <i class="ph-pencil me-1"></i> Sửa
                </a>
                @endcan
                <a href="{{ route('feedbacks.index') }}" class="btn btn-secondary">
                    <i class="ph-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <h6>Thông tin phản ánh:</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th width="20%">Tiêu đề</th>
                            <td>{{ $feedback->title }}</td>
                        </tr>
                        <tr>
                            <th>Lớp</th>
                            <td>{{ $feedback->class->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Người tạo</th>
                            <td>{{ $feedback->student->full_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Ngày tạo</th>
                            <td>{{ $feedback->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                <span class="badge {{ $feedback->status->badgeColor() }}">{{ $feedback->status->label() }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Nội dung</th>
                            <td>{{ $feedback->content }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @can('updateStatus', $feedback)
            <div class="mb-3">
                <h6>Cập nhật trạng thái:</h6>
                <form wire:submit="updateStatus" class="d-flex gap-2">
                    <select class="form-select" wire:model="newStatus">
                        <option value="">Chọn trạng thái</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
            @endcan
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Phản hồi</h5>
        </div>

        <div class="card-body">
            @if($replies->count() > 0)
                <div class="mb-3">
                    @foreach($replies as $reply)
                        <div class="card mb-2">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $reply->user->full_name ?? 'N/A' }}</strong>
                                    <small class="text-muted ms-2">{{ $reply->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                            <div class="card-body">
                                {{ $reply->content }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center">Chưa có phản hồi nào.</p>
            @endif

            @can('reply', $feedback)
            <div class="mt-3">
                <h6>Thêm phản hồi:</h6>
                <form wire:submit="addReply">
                    <div class="mb-3">
                        <textarea class="form-control @error('replyContent') is-invalid @enderror" wire:model="replyContent" rows="3" placeholder="Nhập nội dung phản hồi..."></textarea>
                        @error('replyContent')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
                    </div>
                </form>
            </div>
            @endcan
        </div>
    </div>
</div>