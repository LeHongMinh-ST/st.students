<div class="d-lg-flex align-items-lg-start">

    <!-- Left sidebar component -->
    <div class="bg-transparent d-block shadow-none sidebar sidebar-component sidebar-expand-lg me-lg-3">

        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- Navigation -->
            <div class="card">
                <ul class="nav nav-sidebar" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#" class="nav-link @if ($tab == 'profile') active @endif" wire:click="setTab('profile')">
                            <i class="mr-2 ph-user"></i>
                            Thông tin người dùng
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a href="#" class="nav-link @if ($tab == 'permission') active @endif" wire:click="setTab('permission')">
                            <i class="mr-2 ph-gear"></i>
                            Phân quyền
                        </a>
                    </li>

                    <li class="nav-item-divider"></li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ config('auth.sso.uri') }}/users/{{ $userData['id'] }}" class="nav-link">
                            <i class="mr-2 ph-note-pencil"></i><span>Chỉnh sửa</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- /navigation -->

        </div>
        <!-- /sidebar content -->

    </div>
    <!-- /left sidebar component -->

    <!-- Right content -->
    <div class="tab-content flex-fill">
        <div class="tab-pane fade @if ($tab == 'profile') show active @endif" id="profile" role="tabpanel">
            <div class="card">

                <div class="card-body">
                    <div class="row">
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
                            <p>
                                <b>Vai trò:</b>
                                @if($user->type)
                                    <x-user-type-badge :type="$user->type" />
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @can('assignRole', \App\Models\User::class)
            <div class="tab-pane fade @if ($tab == 'permission') active show @endif" id="permission" role="tabpanel">

                <div class="card">
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
                                    <th width="40%">Tên vai trò</th>
                                    <th width="40%">Mô tả</th>
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
                                        <td width="40%">
                                            <span class="fw-semibold">
                                                {{ $item->name }}
                                            </span>
                                        </td>
                                        <td width="40%">{{ $item->description }}</td>

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
    <!-- /right content -->

</div>
