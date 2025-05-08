<div>
    <div x-data="{ isFilterOpen: false }">
        <div class="card">
            <div class="py-3 card-header d-flex justify-content-between">
                <div class="gap-2 d-flex">
                    <div>
                        <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Tìm kiếm...">
                    </div>
                    <div>
                        <button class="btn btn-light" type="button" @click="isFilterOpen = !isFilterOpen" :class="{ 'active': isFilterOpen }">
                            <i class="ph-funnel"></i> Bộ lọc
                        </button>
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

            <div x-show="isFilterOpen" x-transition class="border-bottom">
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold mb-3">Trạng thái</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn {{ $filter === 'all' ? 'btn-primary' : 'btn-light' }} d-flex align-items-center gap-2" wire:click="setFilter('all')">
                                    <i class="ph-users"></i>
                                    <span>Tất cả</span>
                                </button>
                                <button type="button" class="btn {{ $filter === 'currently_studying' ? 'btn-primary' : 'btn-light' }} d-flex align-items-center gap-2" wire:click="setFilter('currently_studying')">
                                    <i class="ph-student"></i>
                                    <span>Đang học</span>
                                </button>
                                <button type="button" class="btn {{ $filter === 'graduated' ? 'btn-primary' : 'btn-light' }} d-flex align-items-center gap-2" wire:click="setFilter('graduated')">
                                    <i class="ph-graduation-cap"></i>
                                    <span>Đã tốt nghiệp</span>
                                </button>
                                <button type="button" class="btn {{ $filter === 'deferred' ? 'btn-primary' : 'btn-light' }} d-flex align-items-center gap-2" wire:click="setFilter('deferred')">
                                    <i class="ph-clock"></i>
                                    <span>Tạm hoãn</span>
                                </button>
                                <button type="button" class="btn {{ $filter === 'temporarily_suspended' ? 'btn-primary' : 'btn-light' }} d-flex align-items-center gap-2" wire:click="setFilter('temporarily_suspended')">
                                    <i class="ph-pause-circle"></i>
                                    <span>Tạm đình chỉ</span>
                                </button>
                                <button type="button" class="btn {{ $filter === 'expelled' ? 'btn-primary' : 'btn-light' }} d-flex align-items-center gap-2" wire:click="setFilter('expelled')">
                                    <i class="ph-x-circle"></i>
                                    <span>Buộc thôi học</span>
                                </button>
                                <button type="button" class="btn {{ $filter === 'to_drop_out' ? 'btn-primary' : 'btn-light' }} d-flex align-items-center gap-2" wire:click="setFilter('to_drop_out')">
                                    <i class="ph-sign-out"></i>
                                    <span>Thôi học</span>
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold mb-3">Cảnh báo</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn {{ $warning === 'all' ? 'btn-primary' : 'btn-light' }} d-flex align-items-center gap-2" wire:click="setWarning('all')">
                                    <i class="ph-circle"></i>
                                    <span>Tất cả</span>
                                </button>
                                <button type="button" class="btn {{ $warning === 'has_warning' ? 'btn-primary' : 'btn-light' }} d-flex align-items-center gap-2" wire:click="setWarning('has_warning')">
                                    <i class="ph-warning"></i>
                                    <span>Có cảnh báo</span>
                                </button>
                                <button type="button" class="btn {{ $warning === 'no_warning' ? 'btn-primary' : 'btn-light' }} d-flex align-items-center gap-2" wire:click="setWarning('no_warning')">
                                    <i class="ph-check-circle"></i>
                                    <span>Không có cảnh báo</span>
                                </button>
                            </div>
                        </div>
                    </div>
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
                            <x-table-empty :colspan="10" />
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{ $students->links('vendor.pagination.theme') }}
    </div>
</div>
