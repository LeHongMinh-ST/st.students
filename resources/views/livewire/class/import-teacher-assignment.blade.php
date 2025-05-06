<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Import phân công giáo viên chủ nhiệm</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <div class="d-flex">
                    <i class="ph-info me-2"></i>
                    <div>
                        <h6 class="fw-semibold">Hướng dẫn import</h6>
                        <p>
                            Tải file mẫu và điền thông tin phân công giáo viên chủ nhiệm theo định dạng sau:
                            <ul>
                                <li>Cột A: Mã lớp - Mã lớp học trong hệ thống</li>
                                <li>Cột B: Mã giảng viên - Mã giảng viên từ hệ thống SSO</li>
                                <li>Cột C: Năm học - Định dạng: YYYY-YYYY (ví dụ: 2023-2024)</li>
                            </ul>
                        </p>
                        <a href="{{ asset('templates/teacher_assignment_template.xlsx') }}" class="btn btn-light">
                            <i class="ph-download me-2"></i> Tải file mẫu
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="startImport">
                        <div class="mb-3">
                            <label class="form-label">Chọn file Excel (.xlsx, .xls, .csv)</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" wire:model.live="file" accept=".xlsx,.xls,.csv">
                            @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" @if(!$file) disabled @endif>
                                <i class="ph-upload-simple me-1"></i> Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if (count($previewData) > 0)
                <div class="mt-3 card" wire:transition>
                    <div class="card-header">
                        <div class="fw-bold"><i class="mr-1 ph-file-text"></i>Xem trước dữ liệu ({{ count($previewData) }} bản ghi)</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="table-light">
                                        <th width="5%">STT</th>
                                        <th width="30%">Mã lớp</th>
                                        <th width="30%">Mã giảng viên</th>
                                        <th width="35%">Năm học</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($previewData as $item)
                                        <tr>
                                            <td>{{ $item['stt'] }}</td>
                                            <td>{{ $item['ma_lop'] }}</td>
                                            <td>{{ $item['ma_giang_vien'] }}</td>
                                            <td>{{ $item['nam_hoc'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal xử lý import -->
    <div wire:ignore.self class="modal fade" id="process-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Đang xử lý import</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">0%</div>
                        </div>
                    </div>
                    <div id="import-status" class="mb-3">
                        <p>Đang xử lý, vui lòng đợi...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        // Xử lý mở modal
        window.addEventListener('onOpenProcessModal', () => {
            $('#process-modal').modal('show');
        });

        // Xử lý cập nhật tiến trình
        window.addEventListener('echo:import.progress.{{ auth()->id() }},.import.progress.updated', (event) => {
            const progress = event.detail.progress;
            const successCount = event.detail.successCount;
            const errorCount = event.detail.errorCount;
            
            $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress).text(progress + '%');
            $('#import-status').html(`<p>Đã xử lý: ${successCount} thành công, ${errorCount} lỗi</p>`);
        });

        // Xử lý khi import hoàn tất
        window.addEventListener('echo:import.progress.{{ auth()->id() }},.import.finished', (event) => {
            const successCount = event.detail.successCount;
            const errorCount = event.detail.errorCount;
            const status = event.detail.status;
            
            $('.progress-bar').css('width', '100%').attr('aria-valuenow', 100).text('100%');
            
            if (status === 'completed') {
                $('#import-status').html(`<p class="text-success">Import hoàn tất: ${successCount} bản ghi thành công</p>`);
            } else if (status === 'partially_failed') {
                $('#import-status').html(`<p class="text-warning">Import hoàn tất với lỗi: ${successCount} thành công, ${errorCount} lỗi</p>`);
            } else {
                $('#import-status').html(`<p class="text-danger">Import thất bại: ${errorCount} lỗi</p>`);
            }
        });
    </script>
    @endscript
</div>
