<div>

    <div class="py-1 mb-3">
        <h5 class="mb-0">
            Khóa {{ $admissionYear->admission_year }}
        </h5>
        <span class="text-muted">Năm học {{ $admissionYear->school_year }}</span>
    </div>


    <div class="mt-3 border-0 shadow-lg card">



        <div class="card-body">

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link  @if ($tab == 'import') fw-bold active @endif" wire:click="setTab('import')">
                        Import danh sách sinh viên
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link @if ($tab == 'history') fw-bold active @endif" wire:click="setTab('history')">
                        Lịch sử
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade @if ($tab == 'import') show active @endif" id="import" role="tabpanel">
                    <div class="mt-3 border-0 shadow-lg card">
                        <div class="card-body">
                            <livewire:commons.import-zone :tempFile="'template_course.xlsx'" />

                        </div>
                    </div>

                    @if (count($previewData) > 0)
                        <div class="mt-3 border-0 shadow-lg card" wire:transition>
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
                                        @forelse ($previewData as $row)
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
                                                <td>{{ $row['dia_chi_bao_tin'] }}</td>
                                                <td>{{ $row['email'] }}</td>
                                                <td>{{ $row['ho_ten_bo'] }}</td>
                                                <td>{{ $row['sdt_cua_bo'] }}</td>
                                                <td>{{ $row['ho_ten_me'] }}</td>
                                                <td>{{ $row['sdt_cua_me'] }}</td>
                                            </tr>

                                        @empty
                                            <x-table-empty :colspan="17" />
                                        @endforelse
                                </table>
                                </tbody>
                            </div>

                        </div>
                    @endif
                </div>

                <div class="tab-pane fade @if ($tab == 'history') show active @endif" id="history" role="tabpanel">
                    <div class="mt-3 border-0 shadow-lg card">
                        <div class="card-header">
                            <div class="fw-bold"><i class="mr-1 ph-file-text"></i>Lịch sử import</div>
                        </div>

                        <div class="table-responsive table-container">
                            <table class="table fs-table">
                                <thead>
                                    <tr class="table-light">
                                        <th>STT</th>
                                        <th>Tên tệp</th>
                                        <th>Trạng thái</th>
                                        <th>Tổng số bản ghi</th>
                                        <th>Sô bản ghi thành công</th>
                                        <th>Số bản ghi thất bại</th>
                                        <th>Người thực hiện</th>
                                        <th>Thời gian</th>
                                        {{-- <th class="text-center">Hành động</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($histories as $item)
                                        <tr>
                                            <td class="text-center" width="5%">{{ $loop->index + 1 + $histories->perPage() * ($histories->currentPage() - 1) }}</td>
                                            <td>{{ $item->file_name }}</td>
                                            <td><x-import-status-badge :status="$item->status" /></td>
                                            <td>{{ $item->total_records ?? 0 }}</td>
                                            <td>{{ $item->successful_records ?? 0 }}</td>
                                            <td>{{ $item->total_records - $item->successful_records }}</td>
                                            <td>{{ $item->user?->full_name }}</td>
                                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                            {{-- <td class="text-center"> --}}
                                            {{--    <a href="text-muted" data-bs-popup="tooltip" title="Tải xuống bản ghi lỗi"> --}}
                                            {{--        <i class="ph-download-simple"></i> --}}
                                            {{--    </a> --}}
                                            {{-- </td> --}}
                                        </tr>

                                    @empty
                                        <x-table-empty :colspan="9" />
                                    @endforelse
                            </table>
                            </tbody>
                        </div>

                    </div>

                    {{ $histories->links('vendor.pagination.theme') }}
                </div>
            </div>

        </div>
    </div>


</div>
