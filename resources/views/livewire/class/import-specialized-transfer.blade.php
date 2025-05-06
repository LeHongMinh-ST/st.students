<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Phân lớp chuyên ngành</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <div class="d-flex">
                    <i class="ph-info me-2"></i>
                    <div>
                        <h6 class="fw-semibold">Hướng dẫn phân lớp chuyên ngành</h6>
                        <p>
                            Tải file mẫu và điền thông tin sinh viên cần chuyển lớp chuyên ngành theo định dạng sau:
                            <ul>
                                <li>Cột A: Mã sinh viên</li>
                                <li>Cột B: Họ và tên (tham khảo)</li>
                                <li>Cột C: Lớp hiện tại (tham khảo)</li>
                                <li>Cột D: Lớp chuyên ngành (mã lớp)</li>
                                <li>Cột E: Năm học chuyển lớp (định dạng: 2023-2024)</li>
                            </ul>
                        </p>
                        <a href="{{ asset('templates/specialized_class_transfer_template.xlsx') }}" class="btn btn-light">
                            <i class="ph-download me-2"></i> Tải file mẫu
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <form wire:submit.prevent="startImport">
                    <div class="mb-3">
                        <label for="file" class="form-label">Chọn file Excel</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" wire:model="file">
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>

            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($showPreview && count($previewData) > 0)
                <div class="mt-4">
                    <h6 class="fw-semibold">Xem trước dữ liệu ({{ count($previewData) }} bản ghi)</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã sinh viên</th>
                                    <th>Họ và tên</th>
                                    <th>Lớp hiện tại</th>
                                    <th>Lớp chuyên ngành</th>
                                    <th>Năm học</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($previewData as $row)
                                    <tr>
                                        <td>{{ $row['stt'] }}</td>
                                        <td>{{ $row['ma_sv'] }}</td>
                                        <td>{{ $row['ho_ten'] }}</td>
                                        <td>{{ $row['lop_hien_tai'] }}</td>
                                        <td>{{ $row['lop_chuyen_nganh'] }}</td>
                                        <td>{{ $row['nam_hoc'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-primary" wire:click="startImport">
                            <i class="ph-upload me-2"></i> Import dữ liệu
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Import Progress Modal -->
    <div wire:ignore>
        <div id="import-progress-modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Đang xử lý import</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <p class="text-center mt-3">Quá trình import đang được xử lý trong hệ thống. Vui lòng đợi...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                const importProgressModal = new bootstrap.Modal(document.getElementById('import-progress-modal'));
                
                @this.on('onOpenProcessModal', () => {
                    importProgressModal.show();
                });
            });
        </script>
    @endpush
</div>
