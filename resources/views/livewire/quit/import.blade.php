<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Import sinh viên buộc thôi học</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <div class="d-flex">
                    <i class="ph-info me-2"></i>
                    <div>
                        <h6 class="fw-semibold">Hướng dẫn import</h6>
                        <p>
                            Tải file mẫu và điền thông tin sinh viên buộc thôi học theo định dạng sau:
                            <ul>
                                <li>Cột A: Mã sinh viên</li>
                                <li>Cột B: Họ và tên</li>
                                <li>Cột C: Loại thôi học (Tự thôi học, Tạm dừng học tập, Buộc thôi học)</li>
                                <li>Cột D: Lý do thôi học</li>
                            </ul>
                        </p>
                        <a href="{{ asset('templates/quit_students_template.xlsx') }}" class="btn btn-light">
                            <i class="ph-download me-2"></i> Tải file mẫu
                        </a>
                    </div>
                </div>
            </div>

            <form wire:submit.prevent="startImport">
                <div class="mb-3">
                    <label for="file" class="form-label">Chọn file Excel (.xlsx, .xls, .csv)</label>
                    <input wire:model="file" type="file" class="form-control @error('file') is-invalid @enderror" id="file" accept=".xlsx,.xls,.csv">
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <a href="{{ route('quits.show', $quit->id) }}" class="btn btn-light">Hủy</a>
                    <button type="submit" class="btn btn-primary" @if(!$file) disabled @endif>
                        <i class="ph-upload-simple me-2"></i> Import
                    </button>
                </div>
            </form>

            @if($showPreview && count($previewData) > 0)
                <div class="mt-4">
                    <h6 class="fw-semibold">Xem trước dữ liệu ({{ count($previewData) }} sinh viên)</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-light">
                                    <th>STT</th>
                                    <th>Mã sinh viên</th>
                                    <th>Họ và tên</th>
                                    <th>Loại thôi học</th>
                                    <th>Lý do</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($previewData as $row)
                                    <tr>
                                        <td>{{ $row['stt'] }}</td>
                                        <td>{{ $row['ma_sv'] }}</td>
                                        <td>{{ $row['ho_ten'] }}</td>
                                        <td>{{ $row['loai_thoi_hoc'] }}</td>
                                        <td>{{ $row['ly_do'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-success" wire:click="startImport">
                            <i class="ph-check-circle me-2"></i> Xác nhận import
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal xử lý import -->
    <div wire:ignore>
        <div id="modal_process" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Đang xử lý import</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Hệ thống đang xử lý import sinh viên buộc thôi học. Vui lòng đợi...</p>
                        <p>Bạn có thể đóng cửa sổ này và quay lại sau. Quá trình import vẫn tiếp tục trong hệ thống.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <a href="{{ route('quits.show', $quit->id) }}" class="btn btn-primary">Quay lại danh sách</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @script
        <script>
            const processModal = new bootstrap.Modal(document.getElementById('modal_process'));
            
            window.addEventListener('onOpenProcessModal', () => {
                processModal.show();
            });
        </script>
    @endscript
</div>
