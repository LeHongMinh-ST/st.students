<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Theo dõi hoạt động</h5>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="ph-magnifying-glass"></i></span>
                        <input type="text" class="form-control" placeholder="Tìm kiếm theo tên người dùng, hành động hoặc chi tiết..." wire:model.live.debounce.300ms="search">
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-light" wire:click="resetFilters">
                        <i class="ph-arrows-counter-clockwise me-1"></i>
                        Đặt lại bộ lọc
                    </button>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Từ ngày:</label>
                    <input type="date" class="form-control" wire:model.live="fromDate">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Đến ngày:</label>
                    <input type="date" class="form-control" wire:model.live="toDate">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table fs-table">
                <thead>
                <tr class="table-light">
                    <th width="5%">STT</th>
                    <th width="15%">Thời gian</th>
                    <th width="15%">Người dùng</th>
                    <th width="20%">Hành động</th>
                    <th width="35%">Chi tiết</th>
                    <th width="10%">Địa chỉ IP</th>
                </tr>
                </thead>
                <tbody>
                @forelse($activities as $index => $activity)
                    <tr>
                        <td class="text-center">{{ $loop->index + 1 + $activities->perPage() * ($activities->currentPage() - 1) }}</td>
                        <td>{{ $activity->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $activity->user_name }}</td>
                        <td>{{ $activity->action }}</td>
                        <td>
                            @if(strlen($activity->details) > 50)
                                {{ Str::limit($activity->details, 50) }}
                                <a href="javascript:void(0);" wire:click="viewDetail({{ $activity->id }})" class="text-primary ms-1">
                                    Xem chi tiết
                                </a>
                            @else
                                {{ $activity->details }}
                            @endif
                        </td>
                        <td>{{ $activity->ip_address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có dữ liệu</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{ $activities->links('vendor.pagination.theme') }}

    <!-- Modal xem chi tiết hoạt động -->
    @if($showDetailModal)
    <div class="modal fade show" id="modal_activity_detail" tabindex="-1" aria-modal="true" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết hoạt động</h5>
                    <button type="button" class="btn-close" wire:click="closeDetail"></button>
                </div>

                <div class="modal-body">
                    @if($viewingActivity)
                        <div class="mb-3">
                            <h6 class="fw-semibold">Thời gian:</h6>
                            <p>{{ $viewingActivity->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-semibold">Người dùng:</h6>
                            <p>{{ $viewingActivity->user_name }}</p>
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-semibold">Hành động:</h6>
                            <p>{{ $viewingActivity->action }}</p>
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-semibold">Chi tiết:</h6>
                            <div class="card bg-light">
                                <div class="card-body" style="white-space: pre-line;">
                                    {{ $viewingActivity->details }}
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-semibold">Địa chỉ IP:</h6>
                            <p>{{ $viewingActivity->ip_address }}</p>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Không tìm thấy thông tin hoạt động.
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="closeDetail">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('livewire:initialized', function () {
            // Khi modal hiển thị, thêm class 'modal-open' vào body để ngăn cuộn trang
            @this.on('showModal', function () {
                document.body.classList.add('modal-open');
                document.body.style.overflow = 'hidden';
                document.body.style.paddingRight = '15px';
            });

            // Khi modal đóng, xóa class 'modal-open' khỏi body
            @this.on('hideModal', function () {
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            });
        });
    </script>
</div>
