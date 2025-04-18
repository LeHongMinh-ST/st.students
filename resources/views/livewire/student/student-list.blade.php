<div>
    <div class="card">
        <div class="py-3 card-header d-flex justify-content-between">
            <div class="gap-2 d-flex">
                <div>
                    <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Tìm kiếm...">
                </div>
            </div>
            <div class="gap-2 d-flex">
                @can('create', \App\Models\Student::class)
                    <div>
                        <a href="{{ route('students.import', ['admission_year' => $admissionYear->id]) }}" type="button" class="px-2 shadow btn btn-success btn-icon fw-semibold">
                            <i class="px-1 ph-file-xls"></i><span>Import Sinh viên</span>
                        </a>
                    </div>
                @endcan

            </div>
        </div>

        <div class="table-responsive table-preview">
            <div wire:loading class="my-3 text-center w-100">
                <span class="spinner-border spinner-border-sm"></span> Đang tải dữ liệu...
            </div>
            <table class="table fs-table" wire:loading.remove>
                <thead>
                    <tr class="table-light">
                        <th width="5%" class="text-center">STT</th>
                        <th width="20%">Sinh viên</th>
                        <th>Mã sinh viên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Ngày sinh</th>
                        <th>Lớp hiện tại</th>
                        <th>Trạng thái</th>
                        <th>Cảnh báo</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $item)
                        <tr>
                            <td class="text-center" width="5%">{{ $loop->index + 1 + $students->perPage() * ($students->currentPage() - 1) }}</td>
                            <td width="25%">
                                <a class="fw-semibold" href="{{ route('students.show', $item->id) }}">
                                    <div class="gap-2 d-flex align-items-center">

                                        <img src="{{ Avatar::create($item->fullName)->toBase64() }}" class="w-32px h-32px" alt="">
                                        <div class="flex-grow-1">
                                            <div>
                                                {{ $item->fullName }}
                                            </div>
                                            <div class="text-muted">
                                                {{ $item->email_edu }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </td>
                            <td>{{ $item->code ?? '-' }}</td>
                            <td>{{ $item->email ? $item->email : '-' }}</td>
                            <td>{{ $item->phone ? $item->phone : '-' }}</td>
                            <td>{{ $item->dobString }}</td>
                            <td>{{ $item->currentClass->name }}</td>
                            <td>
                                <x-student-status-badge :status="$item->status" />
                            </td>
                            <td>
                                @if($item->warningLevel)
                                    <span class="badge {{ $item->warningLevel->badgeColor() }}">{{ $item->warningLevel->label() }}</span>
                                @endif
                            </td>
                            <td width="10%">{{ $item->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <x-table-empty :colspan="6" />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $students->links('vendor.pagination.theme') }}
</div>
