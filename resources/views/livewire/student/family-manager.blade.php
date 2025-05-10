<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="ph-users-three me-2"></i>Thông tin gia đình</h5>
            @can('create', [App\Models\Family::class, $student])
                <button type="button" class="btn btn-primary btn-sm" wire:click="openAddModal">
                    <i class="ph-plus me-1"></i> Thêm thành viên
                </button>
            @endcan
        </div>

        @if($families->isEmpty())
            <div class="alert alert-info m-3">
                <i class="ph-info me-2"></i> Chưa có thông tin gia đình.
            </div>
        @else
            <div class="table-responsive">
                <table class="table fs-table">
                    <thead>
                        <tr class="table-light">
                            <th style="width: 5%">STT</th>
                            <th style="width: 20%">Mối quan hệ</th>
                            <th style="width: 25%">Họ và tên</th>
                            <th style="width: 25%">Nghề nghiệp</th>
                            <th style="width: 15%">Số điện thoại</th>
                            @can('update', $student)
                                <th style="width: 10%">Thao tác</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($families as $index => $family)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <x-family-relationship-badge :relationship="$family->relationship" />
                                </td>
                                <td style="word-break: break-word">{{ $family->full_name ?: 'N/A' }}</td>
                                <td style="word-break: break-word">{{ $family->job ?: 'N/A' }}</td>
                                <td style="word-break: break-word">{{ $family->phone ?: 'N/A' }}</td>
                                @can('update', $student)
                                <td>
                                    <div class="d-inline-flex">
                                        @can('update', $family)
                                            <button type="button" class="btn btn-link text-primary p-0 me-2" wire:click="openEditModal({{ $family->id }})" title="Chỉnh sửa">
                                                <i class="ph-pencil"></i>
                                            </button>
                                        @endcan

                                        @can('delete', $family)
                                            <button type="button" class="btn btn-link text-danger p-0" wire:click="openDeleteModal({{ $family->id }})" title="Xóa">
                                                <i class="ph-trash"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Modal thêm thành viên gia đình -->
    @if($showAddModal)
    <div class="modal fade show" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm thành viên gia đình</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="relationship" class="form-label">Mối quan hệ <span class="text-danger">*</span></label>
                        <select class="form-select @error('relationship') is-invalid @enderror" id="relationship" wire:model="relationship">
                            <option value="">-- Chọn mối quan hệ --</option>
                            @foreach($relationships as $rel)
                                <option value="{{ $rel->value }}">{{ $rel->label() }}</option>
                            @endforeach
                        </select>
                        @error('relationship')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" wire:model="full_name">
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="job" class="form-label">Nghề nghiệp</label>
                        <input type="text" class="form-control @error('job') is-invalid @enderror" id="job" wire:model="job">
                        @error('job')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" wire:model="phone">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" wire:click="closeModal">Hủy</button>
                    <button type="button" class="btn btn-primary" wire:click="save">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal chỉnh sửa thành viên gia đình -->
    @if($showEditModal)
    <div class="modal fade show" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa thành viên gia đình</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_relationship" class="form-label">Mối quan hệ <span class="text-danger">*</span></label>
                        <select class="form-select @error('relationship') is-invalid @enderror" id="edit_relationship" wire:model="relationship">
                            <option value="">-- Chọn mối quan hệ --</option>
                            @foreach($relationships as $rel)
                                <option value="{{ $rel->value }}">{{ $rel->label() }}</option>
                            @endforeach
                        </select>
                        @error('relationship')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_full_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="edit_full_name" wire:model="full_name">
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_job" class="form-label">Nghề nghiệp</label>
                        <input type="text" class="form-control @error('job') is-invalid @enderror" id="edit_job" wire:model="job">
                        @error('job')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="edit_phone" wire:model="phone">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" wire:click="closeModal">Hủy</button>
                    <button type="button" class="btn btn-primary" wire:click="save">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal xác nhận xóa -->
    @if($showDeleteModal)
    <div class="modal fade show" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa thông tin thành viên gia đình này?</p>
                    <p class="text-danger">Lưu ý: Hành động này không thể hoàn tác.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" wire:click="closeModal">Hủy</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Xóa</button>
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
