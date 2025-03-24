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

        <div class="table-responsive">
            <div wire:loading class="my-3 text-center w-100">
                <span class="spinner-border spinner-border-sm"></span> Đang tải dữ liệu...
            </div>
            <table class="table fs-table" wire:loading.remove>
                <thead>
                    <tr class="table-light">
                        <th width="5%">STT</th>
                        <th width="35%">Sinh viên</th>
                        <th>Mã sinh viên</th>
                        <th>Lớp</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $item)
                        <tr>
                            <td class="text-center" width="5%">{{ $loop->index + 1 + $students->perPage() * ($roles->currentPage() - 1) }}</td>
                            <td width="35%">
                                <a class="fw-semibold" href="{{ route('students.show', $item->id) }}">
                                    <img src="{{ Avatar::create($item->full_name)->toBase64() }}" class="w-32px h-32px" alt="">
                                    {{ $item->full_name }}
                                </a>
                            </td>
                            <td>{{ $item->code }}</td>
                            <td></td>
                            <td></td>
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
