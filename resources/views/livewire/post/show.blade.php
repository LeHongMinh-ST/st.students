<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ $post->title }}</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span class="text-muted">Đăng bởi: {{ $post->user->full_name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <x-post-status-badge :status="$post->status" />
                        <span class="text-muted ms-2">{{ $post->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <hr>
                <div class="post-content">
                    {!! $post->content !!}
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('posts.index') }}" class="btn btn-light">Quay lại</a>
                @can('update', $post)
                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">Chỉnh sửa <i class="ph-pencil ms-2"></i></a>
                @endcan
            </div>
        </div>
    </div>
</div>
