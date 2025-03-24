<div>
    <div class="border-0 shadow-lg card">
        <div class="card-body">
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="ph-file-arrow-up"></i>Chọn file để tải lên</label>
                    <div class="p-4 text-center dropzone d-block" id="dropzoneFile" wire:ignore.self>
                        <div class="mt-4">
                            <i class="ph-cloud-arrow-up display-4"></i>
                        </div>
                        <div>
                            <p class="text-muted">{{ $fileName }}</p>
                            <input type="file" name="file" wire:model.live="file" class="d-none" id="fileInput" accept=".xls,.xlsx,.csv">
                        </div>
                    </div>
                </div>

                <!-- Nút tải file mẫu -->
                <div class="mb-3">
                    <a href="{{ route('file.download-template', ['name' => 'template_course.xlsx']) }}">
                        <i class="ph-download-simple me-1"></i> Tải file mẫu
                    </a>
                </div>

                @if ($file)
                    <div class="text-center">
                        <button type="submit" class="mt-3 btn btn-primary">
                            <i class="ph-cloud-arrow-up me-1"></i> Tải lên
                        </button>
                    </div>
                @endif
            </form>

            {{-- <div id="uploadMessage" class="mt-3 alert d-none"></div> --}}
        </div>
    </div>

    @if ($previewData)
        <div class="mt-4 border-0 shadow-lg card">
            <div class="card-header">
                <div class="fw-bold"><i class="ph-file-text"></i>Xem trước dữ liệu</div>
            </div>
            <div wire:loading wire:target="previewFile" class="my-3 text-center w-100">
                <span class="spinner-border spinner-border-sm"></span> Đang tải dữ liệu...
            </div>
            <div class="table-responsive">
                <table class="table fs-table" wire:loading.remove>
                    <thead>
                        <tr class="table-light">
                            <th>Mã nhập học</th>
                            <th>Mã SV</th>
                            <th>Họ tên</th>
                            <th>Ngày sinh</th>
                            <th>Giới tính</th>
                            <th>Lớp</th>
                            <th>Khoa</th>
                            <th>Niên khóa</th>
                            <th>Dân tộc</th>
                            <th>Điện thoại</th>
                            <th>Email</th>
                            <th>Địa chỉ báo tin</th>
                            <th>Họ tên bố</th>
                            <th>SĐT của bố</th>
                            <th>Họ tên mẹ</th>
                            <th>SĐT của mẹ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($previewData as $row)
                            <tr>
                                <td>{{ $row['ma_nhap_hoc'] }}</td>
                                <td>{{ $row['ma_sv'] }}</td>
                                <td>{{ $row['ho_ten'] }}</td>
                                <td>{{ $row['ngay_sinh'] }}</td>
                                <td>{{ $row['gioi_tinh'] }}</td>
                                <td>{{ $row['lop'] }}</td>
                                <td>{{ $row['khoa'] }}</td>
                                <td>{{ $row['nien_khoa'] }}</td>
                                <td>{{ $row['dan_toc'] }}</td>
                                <td>{{ $row['dien_thoai'] }}</td>
                                <td>{{ $row['email'] }}</td>
                                <td>{{ $row['dia_chi_bao_tin'] }}</td>
                                <td>{{ $row['ho_ten_bo'] }}</td>
                                <td>{{ $row['sdt_bo'] }}</td>
                                <td>{{ $row['ho_ten_me'] }}</td>
                                <td>{{ $row['sdt_me'] }}</td>
                            </tr>
                        @endforeach
                </table>
                </tbody>
            </div>

        </div>
    @endif
</div>

@script
    <script>
        $(document).ready(function() {
            let dropzone = $("#dropzoneFile");
            let fileInput = $("#fileInput");

            dropzone.on("click", function(e) {
                if (!$(e.target).is("#fileInput")) {
                    fileInput.trigger("click");
                }
            });

            $(document).on("dragover drop", function(e) {
                e.preventDefault();
                e.stopPropagation();
            });

            dropzone.on("dragover", function(e) {
                e.preventDefault();
                dropzone.addClass("border-primary");
            });

            dropzone.on("dragleave", function() {
                dropzone.removeClass("border-primary");
            });


            dropzone.on("drop", function(e) {
                e.preventDefault();
                dropzone.removeClass("border-primary");

                let files = e.originalEvent.dataTransfer.files;
                if (files.length) {
                    let file = files[0];
                    @this.upload('file', file);
                }
            });

        });
    </script>
@endscript
