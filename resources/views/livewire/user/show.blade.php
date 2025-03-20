<div>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#" class="nav-link @if ($tab == 'profile') active @endif" wire:click="setTab('profile')">
                        <i class="mr-2 ph-user"></i>
                        Thông tin người dùng
                    </a>
                </li>
                @can('assignRole', \App\Models\User::class)
                    <li class="nav-item">
                        <a href="#" class="nav-link @if ($tab == 'permission') active @endif" wire:click="setTab('permission')">
                            <i class="mr-2 ph-gear"></i>
                            Phân quyền
                        </a>
                    </li>
                @endcan
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade @if ($tab == 'profile') show active @endif" id="profile" role="tabpanel">
                    <div class="mt-3 row">
                        <div class="col-md-6 col-12">
                            <p>
                                <b>Tên đăng nhập:</b> {{ $userData['user_name'] }}
                            </p>
                            <p>
                                <b>Email:</b> {{ $userData['email'] }}
                            </p>
                            <p>
                                <b>Số điện thoại:</b> {{ $userData['phone'] }}
                            </p>

                        </div>
                        <div class="col-md-6 col-12">
                            <p>
                                <b>Họ và tên:</b> {{ $userData['full_name'] }}
                            </p>
                            <p>
                                <b>Loại người dùng:</b> <x-role-badge :role="$userData['role']" />
                            </p>
                        </div>
                    </div>
                </div>

                @can('assignRole', \App\Models\User::class)
                    <div class="tab-pane fade @if ($tab == 'permission') active show @endif" id="permission" role="tabpanel">

                        <div class="mt-3 card">
                            <div class="py-3 card-header d-flex justify-content-between">
                                <div class="gap-2 d-flex">
                                    <div>
                                        <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Tìm kiếm...">
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
                                            <th width="5%">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" wire:model.live="selectAll" id="selectAll">
                                                </div>
                                            </th>
                                            <th width="80%">Tên vai trò</th>
                                            <th>Ngày tạo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($roles as $item)
                                            <tr>
                                                <td class="text-center" width="5%">
                                                    <div class="form-check">
                                                        <input type="checkbox" value="{{ $item->id }}" wire:model.live="roleIds" @if (in_array($item->id, $roleIds)) checked @endif class="form-check-input" id="role-{{ $item->id }}">
                                                    </div>
                                                </td>
                                                <td width="80%">
                                                    <span class="fw-semibold">
                                                        {{ $item->name }}
                                                    </span>
                                                </td>

                                                <td width="10%">{{ $item->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @empty
                                            <x-table-empty :colspan="4" />
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $roles->links('vendor.pagination.theme') }}
                    </div>

                @endcan
            </div>
        </div>

    </div>
</div>
