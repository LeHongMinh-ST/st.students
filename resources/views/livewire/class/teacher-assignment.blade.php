<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="ph-users-three me-1"></i> Phân công giáo viên</h5>
            <div>
                @can('manageTeacherAssignment', \App\Models\ClassGenerate::class)
                    <button type="button" class="btn btn-primary" wire:click="openCreateModal">
                        <i class="ph-plus-circle me-1"></i> Phân công mới
                    </button>
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
                    <th width="15%">Năm học</th>
                    <th width="35%">Giáo viên chủ nhiệm</th>
                    <th width="15%">Trạng thái</th>
                    <th width="15%">Thời gian phân công</th>
                    <th width="15%">Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @forelse($assignments as $item)
                    <tr>
                        <td class="text-center" width="5%">{{ $loop->index + 1 + $assignments->perPage() * ($assignments->currentPage() - 1) }}</td>
                        <td width="15%">Năm học {{ $item->year }}</td>
                        <td width="35%">
                            @if($item->teacher)
                                {{ $item->teacher->full_name ?? $item->teacher->name }}
                            @else
                                <span class="text-muted">Chưa phân công</span>
                            @endif
                        </td>
                        <td width="15%">
                            <span class="badge {{ $item->status->value === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $item->status->value === 'active' ? 'Hiện tại' : 'Trước đây' }}
                            </span>
                        </td>
                        <td width="15%">
                            {{ $item->assigned_at ? $item->assigned_at->format('d/m/Y H:i') : ($item->created_at ? $item->created_at->format('d/m/Y H:i') : 'N/A') }}
                        </td>
                        <td width="15%">
                            <div class="d-inline-flex">
                                @can('manageTeacherAssignment', \App\Models\ClassGenerate::class)
                                    <a href="#" class="text-body" wire:click.prevent="openEditModal({{ $item->id }})" data-bs-popup="tooltip" title="Chỉnh sửa">
                                        <i class="ph-pencil"></i>
                                    </a>
                                    <a href="#" class="text-body mx-2" wire:click.prevent="confirmDelete({{ $item->id }})" data-bs-popup="tooltip" title="Xóa">
                                        <i class="ph-trash"></i>
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-table-empty :colspan="7" />
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $assignments->links('vendor.pagination.theme') }}
    </div>

    <!-- Modal for creating/editing assignments -->
    <div id="assignment-modal" class="modal fade" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <form wire:submit.prevent="save">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $modalTitle }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Năm học <span class="text-danger">*</span></label>
                            <select wire:model.live="year" class="form-select @error('year') is-invalid @enderror">
                                <option value="">-- Chọn năm học --</option>
                                @foreach($years as $yearOption)
                                    <option value="{{ $yearOption['value'] }}">{{ $yearOption['label'] }}</option>
                                @endforeach
                            </select>
                            @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3" wire:ignore>
                            <label class="form-label" >Giáo viên chủ nhiệm</label>
                            <select wire:model.live="teacher_id" class="form-select select2-teacher @error('teacher_id') is-invalid @enderror">
                                <option value="">-- Chọn giáo viên chủ nhiệm --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher['id'] }}">{{ $teacher['name'] }}</option>
                                @endforeach
                            </select>
                            @error('teacher_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>



                        <div class="mb-3">
                            <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select wire:model.live="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="">-- Chọn trạng thái --</option>
                                @foreach($statuses as $statusOption)
                                    <option value="{{ $statusOption['value'] }}">{{ $statusOption['label'] }}</option>
                                @endforeach
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @script
    <script>
        // Delete confirmation
        window.addEventListener('openDeleteConfirmation', (event) => {
            const assignmentId = event.detail.assignmentId;
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn không thể hoàn tác hành động này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý, xóa!',
                cancelButtonText: 'Hủy',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-light',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleteAssignment(assignmentId);
                }
            });
        });

        // Hàm khởi tạo Select2
        function initTeacherSelect2() {
            // Hủy Select2 cũ nếu đã khởi tạo
            if ($('.select2-teacher').hasClass('select2-hidden-accessible')) {
                $('.select2-teacher').select2('destroy');
            }

            // Khởi tạo Select2 cho dropdown giáo viên
            setTimeout(function() {
                $('.select2-teacher').select2({
                    dropdownParent: $('#assignment-modal'),
                    width: '100%',
                    placeholder: 'Chọn giáo viên',
                    allowClear: true,
                    language: {
                        noResults: function() {
                            return "Không tìm thấy kết quả";
                        },
                        searching: function() {
                            return "Đang tìm kiếm...";
                        }
                    },
                    // Thêm các tùy chọn để phù hợp với giao diện
                    containerCssClass: 'select-lg',
                    dropdownCssClass: 'select2-dropdown-lg'
                });

                // Xử lý sự kiện thay đổi của Select2 để cập nhật giá trị cho Livewire
                $('.select2-teacher').on('change', function (e) {
                    @this.set('teacher_id', $(this).val());
                });

                // Đặt giá trị hiện tại cho Select2
                if (@this.teacher_id) {
                    $('.select2-teacher').val(@this.teacher_id).trigger('change');
                }
            }, 100);
        }

        // Xử lý mở modal
        window.addEventListener('onOpenAssignmentModal', () => {
            $('#assignment-modal').modal('show');
            initTeacherSelect2();
        });

        // Xử lý đóng modal
        window.addEventListener('onCloseAssignmentModal', () => {
            $('#assignment-modal').modal('hide');
        });

        // Khởi tạo lại Select2 khi Livewire cập nhật DOM
        document.addEventListener('livewire:update', function() {
            if ($('#assignment-modal').hasClass('show')) {
                initTeacherSelect2();
            }
        });
    </script>
    @endscript
</div>
