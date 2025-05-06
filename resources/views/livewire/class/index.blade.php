<div>
    <div class="card">
        <div class="py-3 card-header d-flex justify-content-between">
            <div class="gap-2 d-flex">
                <div>
                    <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Tìm kiếm...">
                </div>
            </div>
            <div class="gap-2 d-flex">
                @can('create', \App\Models\ClassGenerate::class)
                    <div>
                        <a href="{{ route('classes.create') }}" type="button" class="px-2 shadow btn btn-primary btn-icon fw-semibold">
                            <i class="px-1 ph-plus-circle fw-semibold"></i><span>Thêm mới</span>
                        </a>
                    </div>
                @endcan
                @can('manageTeacherAssignment', \App\Models\ClassGenerate::class)
                    <div>
                        <a href="{{ route('classes.import-teacher-assignment') }}" type="button" class="px-2 shadow btn btn-success btn-icon fw-semibold">
                            <i class="px-1 ph-upload-simple fw-semibold"></i><span>Import phân công</span>
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
                        <th width="20%">Tên lớp</th>
                        <th width="40%">Giáo viên chủ nhiệm</th>
                        <th width="10%">Sĩ số</th>
                        <th width="10%">Loại lớp</th>
                        <th width="10%">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $item)
                        <tr>
                            <td class="text-center" width="5%">{{ $loop->index + 1 + $classes->perPage() * ($classes->currentPage() - 1) }}</td>
                            <td width="20%">
                                <a href="{{ route('classes.show', $item->id) }}" class="fw-semibold">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td width="40%">
                                @if(isset($classAssigns[$item->id]['teacher']))
                                    {{ $classAssigns[$item->id]['teacher'] ?? 'Chưa phân công' }}
                                @else
                                    <span class="text-muted">Chưa phân công</span>
                                @endif
                            </td>
                            <td width="10%">{{ $item->students_count }} sinh viên</td>
                            <td width="10%">{{ $item->type->label() }}</td>
                            <td width="10%">
                                <x-class-status-badge :status="$item->status" />
                            </td>
                        </tr>
                    @empty
                        <x-table-empty :colspan="7" />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $classes->links('vendor.pagination.theme') }}
</div>
