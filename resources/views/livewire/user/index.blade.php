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
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Loại người dùng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $key =>  $item)
                        <tr>
                            <td class="text-center" width="5%">{{ $key + 1 + 10 * ($page - 1) }}</td>
                            <td width="30%">
                                <a class="fw-semibold" href="{{ route('user.show', $item['id']) }}">
                                    <img src="{{ Avatar::create($item['full_name'])->toBase64() }}" class="w-32px h-32px" alt="">
                                    {{ $item['full_name'] }}
                                </a>
                            </td>
                            <td>{{ $item['email'] }}</td>
                            <td>{{ $item['phone'] }}</td>
                            <td>{{ $item['role'] }}</td>
                        </tr>
                    @empty
                        <x-table-empty :colspan="5" />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
