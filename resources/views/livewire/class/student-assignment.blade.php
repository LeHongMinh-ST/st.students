<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="ph-users me-1"></i> Phân công cán sự lớp</h5>
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
                    <th width="40%">Sinh viên</th>
                    <th width="20%">Mã sinh viên</th>
                    <th width="20%">Vai trò</th>
                    <th width="15%">Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @forelse($classStudents as $item)
                    <tr>
                        <td class="text-center" width="5%">{{ $loop->index + 1 + $classStudents->perPage() * ($classStudents->currentPage() - 1) }}</td>
                        <td width="40%">
                            <a href="{{ route('students.show', $item->id) }}" class="fw-semibold">
                                {{ $item->full_name }}
                            </a>
                        </td>
                        <td width="20%">{{ $item->code }}</td>
                        <td width="20%">
                            <x-student-role-badge :role="\App\Enums\StudentRole::from($item->pivot->role)" />
                        </td>
                        <td width="15%">
                            <div class="d-inline-flex">
                                @can('manageTeacherAssignment', \App\Models\ClassGenerate::class)
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" wire:click="openEditModal({{ $item->id }}, '{{ $item->pivot->role }}')">
                                        <i class="ph-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmRemoveRole({{ $item->id }})">
                                        <i class="ph-trash"></i>
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-table-empty :colspan="5" />
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $classStudents->links('vendor.pagination.theme') }}
    </div>

    <!-- Modal for creating/editing student assignments -->
    <div id="student-assignment-modal" class="modal fade" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <form wire:submit.prevent="save">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $modalTitle }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Sinh viên <span class="text-danger">*</span></label>
                            <select wire:model.live="student_id" class="form-select @error('student_id') is-invalid @enderror">
                                <option value="">-- Chọn sinh viên --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student['id'] }}">{{ $student['name'] }}</option>
                                @endforeach
                            </select>
                            @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                            <select wire:model.live="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="">-- Chọn vai trò --</option>
                                @foreach($roles as $roleOption)
                                    <option value="{{ $roleOption['value'] }}">{{ $roleOption['label'] }}</option>
                                @endforeach
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
        window.addEventListener('openRemoveRoleConfirmation', (event) => {
            const studentId = event.detail.studentId;
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn không thể hoàn tác hành động này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý, xóa!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.removeRole(studentId);
                }
            });
        });

        // Xử lý mở modal
        window.addEventListener('onOpenStudentAssignmentModal', () => {
            $('#student-assignment-modal').modal('show');
        });

        // Xử lý đóng modal
        window.addEventListener('onCloseStudentAssignmentModal', () => {
            $('#student-assignment-modal').modal('hide');
        });
    </script>
    @endscript
</div>
