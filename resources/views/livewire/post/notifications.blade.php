<div class="nav-item nav-item-dropdown-lg dropdown ms-lg-2">
    <a href="#" class="p-1 navbar-nav-link align-items-center rounded-pill" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ph-bell"></i>
        @if($unreadCount > 0)
            <span class="badge bg-danger rounded-pill position-absolute top-0 end-0">{{ $unreadCount }}</span>
        @endif
    </a>

    <div class="dropdown-menu dropdown-menu-end">
        <div class="dropdown-header d-flex justify-content-between align-items-center">
            <span class="fs-base">Thông báo</span>
            @if($unreadCount > 0)
                <a href="#" wire:click.prevent="markAllAsRead" class="text-body rounded btn btn-sm btn-link">
                    <i class="ph-checks me-1"></i>
                    Đánh dấu tất cả đã đọc
                </a>
            @endif
        </div>

        <div class="dropdown-content-body py-0">
            <div class="dropdown-content-scroll" style="height: 300px">
                @forelse($notifications as $notification)
                    <a href="{{ route('posts.show', $notification['post_id']) }}" class="dropdown-item d-flex align-items-center py-2 {{ $notification['read'] ? 'bg-light' : '' }}" wire:click="markAsRead({{ $notification['id'] }})">
                        <div class="flex-fill">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <img src="{{ asset('assets/images/logo-admin.png') }}" class="w-32px h-32px rounded-pill" alt="">
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $notification['title'] }}</div>
                                    <div class="text-muted">{{ \Illuminate\Support\Str::limit($notification['content'], 50) }}</div>
                                    <div class="fs-sm text-muted mt-1">{{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="dropdown-item d-flex align-items-center py-2">
                        <div class="flex-fill text-center">
                            <span class="text-muted">Không có thông báo</span>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="dropdown-footer border-top text-center py-2">
            <a href="#" class="text-body">Xem tất cả thông báo</a>
        </div>
    </div>
</div>
