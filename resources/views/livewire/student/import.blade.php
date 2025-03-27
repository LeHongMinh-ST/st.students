<div>

    <div class="py-1 mb-3">
        <h5 class="mb-0">
            Khóa {{ $admissionYear->admission_year }}
        </h5>
        <span class="text-muted">Năm học {{ $admissionYear->school_year }}</span>
    </div>
    <div class="border-0 shadow-lg card">
        <div class="card-body">
            <livewire:commons.import-zone :type="\App\Enums\TypeImport::Student" />
        </div>
    </div>


    @if ($previewData)
        <div class="mt-4 border-0 shadow-lg card" wire:transition>
            <div class="card-header">
                <div class="fw-bold"><i class="mr-1 ph-file-text"></i>Xem trước dữ liệu ({{ count($previewData) }} bản ghi)</div>
            </div>

            <div class="table-responsive table-preview table-container">
                <table class="table fs-table">
                    <thead>
                        <tr class="table-light">
                            <th>STT</th>
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
                                <td>{{ $row['stt'] }}</td>
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
                                <td>{{ $row['sdt_cua_bo'] }}</td>
                                <td>{{ $row['ho_ten_me'] }}</td>
                                <td>{{ $row['sdt_cua_me'] }}</td>
                            </tr>
                        @endforeach
                </table>
                </tbody>
            </div>

        </div>
    @endif


</div>
