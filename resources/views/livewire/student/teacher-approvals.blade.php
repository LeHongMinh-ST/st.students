<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="ph-clipboard-text me-2"></i>Danh sách yêu cầu chỉnh sửa thông tin sinh viên</h5>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="ph-magnifying-glass"></i></span>
                        <input type="text" class="form-control" placeholder="Tìm kiếm theo tên hoặc mã sinh viên..." wire:model.live.debounce.300ms="search">
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

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="table-light">
                            <th width="5%">STT</th>
                            <th width="20%">Sinh viên</th>
                            <th width="15%">Mã sinh viên</th>
                            <th width="15%">Ngày yêu cầu</th>
                            <th width="15%">Trạng thái</th>
                            <th width="10%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $index => $request)
                            <tr>
                                <td class="text-center">{{ $index + 1 + ($requests->currentPage() - 1) * $requests->perPage() }}</td>
                                <td>{{ $request->student->full_name ?? 'N/A' }}</td>
                                <td>{{ $request->student->code ?? 'N/A' }}</td>
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
                                <td colspan="6" class="text-center">Không có yêu cầu chỉnh sửa thông tin nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $requests->links('vendor.pagination.theme') }}
            </div>
        </div>
    </div>

    <!-- Modal xem chi tiết và duyệt yêu cầu -->
    <div wire:ignore.self class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalLabel">{{ $modalTitle }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($currentRequest)
                        <div class="mb-3">
                            <h6>Thông tin sinh viên:</h6>
                            <p><strong>Họ tên:</strong> {{ $currentRequest->student->full_name }}</p>
                            <p><strong>Mã sinh viên:</strong> {{ $currentRequest->student->code }}</p>
                            <p><strong>Ngày yêu cầu:</strong> {{ $currentRequest->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Trạng thái:</strong> <span class="badge {{ $currentRequest->status->badgeColor() }}">{{ $currentRequest->status->label() }}</span></p>
                        </div>

                        <div class="mb-3">
                            <h6>Thông tin thay đổi:</h6>
                            @php
                                $changes = json_decode($currentRequest->change_column, true) ?? [];
                            @endphp

                            @if(count($changes) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Trường thông tin</th>
                                                <th>Giá trị cũ</th>
                                                <th>Giá trị mới</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($changes as $field => $change)
                                                <tr>
                                                    <td>
                                                        @switch($field)
                                                            @case('pob')
                                                                Nơi sinh
                                                                @break
                                                            @case('address')
                                                                Địa chỉ
                                                                @break
                                                            @case('permanent_residence')
                                                                Hộ khẩu thường trú
                                                                @break
                                                            @case('countryside')
                                                                Quê quán
                                                                @break
                                                            @case('training_type')
                                                                Loại đào tạo
                                                                @break
                                                            @case('phone')
                                                                Số điện thoại
                                                                @break
                                                            @case('nationality')
                                                                Quốc tịch
                                                                @break
                                                            @case('citizen_identification')
                                                                CCCD/CMND
                                                                @break
                                                            @case('ethnic')
                                                                Dân tộc
                                                                @break
                                                            @case('religion')
                                                                Tôn giáo
                                                                @break
                                                            @case('social_policy_object')
                                                                Đối tượng chính sách
                                                                @break
                                                            @case('note')
                                                                Ghi chú
                                                                @break
                                                            @case('email')
                                                                Email
                                                                @break
                                                            @case('thumbnail')
                                                                Ảnh đại diện
                                                                @break
                                                            @default
                                                                {{ $field }}
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $change['old'] ?? '' }}</td>
                                                    <td>{{ $change['new'] ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p>Không có thông tin thay đổi.</p>
                            @endif
                        </div>

                        @if($currentRequest->status === \App\Enums\StudentUpdateStatus::ClassOfficerApproved)
                            <div class="mb-3">
                                <label for="note" class="form-label">Ghi chú:</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" rows="3" wire:model="note"></textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    @else
                        <p>Không tìm thấy thông tin yêu cầu.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>

                    @if($currentRequest && $currentRequest->status === \App\Enums\StudentUpdateStatus::ClassOfficerApproved)
                        <button type="button" class="btn btn-danger" wire:click="rejectRequest">
                            <i class="ph-x-circle me-1"></i> Từ chối
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="approveRequest">
                            <i class="ph-check-circle me-1"></i> Duyệt
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Đảm bảo Bootstrap được khởi tạo
            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap is not defined. Make sure it is loaded before this script.');
                return;
            }

            const modalElement = document.getElementById('requestModal');
            if (!modalElement) {
                console.error('Modal element not found');
                return;
            }

            let modal;
            try {
                modal = new bootstrap.Modal(modalElement);
            } catch (error) {
                console.error('Error initializing Bootstrap modal:', error);
                return;
            }

            @this.on('alert', (data) => {
                Swal.fire({
                    icon: data.type,
                    title: data.type === 'success' ? 'Thành công!' : 'Lỗi!',
                    text: data.message,
                    confirmButtonText: 'Đóng'
                });
            });

            // Hiển thị modal khi showModal = true
            @this.watch('showModal', (value) => {
                if (value) {
                    try {
                        modal.show();
                    } catch (error) {
                        console.error('Error showing modal:', error);
                    }
                } else {
                    try {
                        modal.hide();
                    } catch (error) {
                        console.error('Error hiding modal:', error);
                    }
                }
            });

            // Đóng modal khi nhấn nút đóng
            modalElement.addEventListener('hidden.bs.modal', () => {
                @this.set('showModal', false);
            });
        });
    </script>
    @endpush
</div>
