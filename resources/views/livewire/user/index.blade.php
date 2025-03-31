<div>
    <div class="card">
        <div class="py-3 card-header">
            <div class="d-flex justify-content-between">

                <div class="flex-wrap gap-2 d-flex">
                    <div>
                        <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Tìm kiếm...">
                    </div>
                </div>
                <div class="gap-2 d-flex">
                    <div>

                    </div>
                </div>
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
                        <th width="30%">Họ và tên</th>
                        <th>Điện thoại</th>
                        <th>Loại tài khoản</th>
                        <th>Vai trò</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $key =>  $item)
                        <tr>
                            <td class="text-center" width="5%">{{ $key + 1 + 10 * ($page - 1) }}</td>
                            <td width="30%">
                                <a class="fw-semibold" href="{{ route('users.show', @$item['local_user']['id']) }}">
                                    <div class="gap-2 d-flex align-items-center">

                                        <img src="{{ Avatar::create($item['full_name'])->toBase64() }}" class="w-32px h-32px" alt="">
                                        <div class="flex-grow-1">
                                            <div>
                                                {{ $item['full_name'] }}
                                            </div>

                                            <div class="text-muted">
                                                {{ $item['email'] }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </td>
                            <td>{{ empty($item['phone']) ? '-' : $item['phone'] }}</td>
                            <td>
                                <x-role-badge :role="$item['role']" />
                            </td>
                            <td>
                                <span data-bs-popup="tooltip" title="{{ @$item['local_user']['role_name'] }}" class="text-role-name">{{ @$item['local_user']['role_name'] ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <x-table-empty :colspan="5" />
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
    <livewire:commons.pagination :currentPage="$page" :totalPages="$totalPages" />
</div>
