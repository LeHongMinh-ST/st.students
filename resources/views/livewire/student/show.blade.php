<div class="d-lg-flex align-items-lg-start">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show w-100 mb-3">
            <i class="ph-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Left sidebar component -->
    <div class="bg-transparent d-block shadow-none sidebar sidebar-component sidebar-expand-lg me-lg-3">

        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- Navigation -->
            <div class="card">
                <ul class="nav nav-sidebar" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#" class="nav-link @if ($tab == 'profile') active @endif"
                           wire:click.prevent="setTab('profile')">
                            <i class="mr-2 ph-user"></i>
                            Thông tin sinh viên
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a href="#" class="nav-link @if ($tab == 'family') active @endif" wire:click="setTab('family')">
                            <i class="mr-2 ph-users-three"></i>
                            Thông tin gia đình
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a href="#" class="nav-link @if ($tab == 'classes') active @endif"
                           wire:click="setTab('classes')">
                            <i class="mr-2 ph-books"></i>
                            Lớp học
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a href="#" class="nav-link @if ($tab == 'update_requests') active @endif"
                           wire:click="setTab('update_requests')">
                            <i class="mr-2 ph-clipboard-text"></i>
                            Yêu cầu chỉnh sửa thông tin
                        </a>
                    </li>

                    @can('update', $student)
                        <li class="nav-item-divider"></li>
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('students.edit-detail', $student->id) }}" class="nav-link">
                                <i class="mr-2 ph-note-pencil"></i><span>Chỉnh sửa</span>
                            </a>
                        </li>
                    @endcan

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

                <div class="card-header">
                    <h5 class="mb-0"><i class="ph-user me-2"></i>Thông tin sinh viên</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-12 col-12 mb-3 mb-sm-4">
                            <div class="pt-0 text-center">
                                <div class="mb-3 card-img-actions d-inline-block">
                                    <img class="img-fluid rounded-circle"
                                         src="{{ Avatar::create($student->fullName)->toBase64() }}" width="150"
                                         height="150" alt="">
                                </div>

                                <h6 class="mb-0">{{ $student->fullName }}</h6>
                                <span class="text-muted">Khoá {{ $student?->admissionYear?->admission_year }}</span>
                                @if ($editStatusMode)
                                    <div class="gap-2 d-flex align-items-center justify-content-center mt-2">
                                        <select class="form-select" wire:model.live="studentStatus">
                                            @foreach (\App\Enums\StudentStatus::cases() as $item)
                                                <option value="{{ $item->value }}"
                                                        @if ($item->value == $student->status) selected @endif>{{ $item->getLabel() }}</option>
                                            @endforeach
                                        </select>
                                        @can('update', $student)
                                        <span class="cursor-pointer" wire:click="$set('editStatusMode', false)">
                                            <i class="mr-2 ph-x"></i>
                                        </span>
                                        @endcan
                                    </div>
                                @else
                                    <div class="gap-2 d-flex align-items-center justify-content-center mt-2">
                                        <x-student-status-badge :status="$student->status"/>
                                        @can('update', $student)
                                        <span class="cursor-pointer" wire:click="$set('editStatusMode', true)">
                                            <i class="mr-2 ph-note-pencil"></i>
                                        </span>
                                        @endcan
                                    </div>
                                @endif

                            </div>

                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-12 col-12">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Thông tin sinh viên</h6>
                                    </div>

                                     <!-- Hiển thị thông tin cơ bản -->
                                     <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <th style="width: 40%">Họ và tên</th>
                                                <td style="word-break: break-word">{{ $student->fullName }}</td>
                                            </tr>
                                            <tr>
                                                <th>Mã sinh viên</th>
                                                <td style="word-break: break-word">{{ $student->code }}</td>
                                            </tr>
                                            <tr>
                                                <th>Ngày sinh</th>
                                                <td style="word-break: break-word">{{ $student->dob ? \Illuminate\Support\Carbon::make($student->dob)->format('d/m/Y') : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Giới tính</th>
                                                <td style="word-break: break-word">{{ $student->gender->value === 'male' ? 'Nam' : 'Nữ' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td style="word-break: break-word">{{ $student->email ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Số điện thoại</th>
                                                <td style="word-break: break-word">{{ $student->phone ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>CCCD/CMND</th>
                                                <td style="word-break: break-word">{{ $student->citizen_identification ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nơi sinh</th>
                                                <td style="word-break: break-word">{{ $student->pob ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Địa chỉ hiện tại</th>
                                                <td style="word-break: break-word">{{ $student->address ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Quê quán</th>
                                                <td style="word-break: break-word">{{ $student->countryside ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Dân tộc</th>
                                                <td style="word-break: break-word">{{ $student->ethnic ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tôn giáo</th>
                                                <td style="word-break: break-word">{{ $student->religion ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Quốc tịch</th>
                                                <td style="word-break: break-word">{{ $student->nationality ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Đối tượng chính sách</th>
                                                <td style="word-break: break-word">{{ $student->social_policy_object ? $student->social_policy_object->label() : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Ghi chú</th>
                                                <td style="word-break: break-word">{{ $student->note ?: 'Chưa có ghi chú' }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade @if ($tab == 'family') active show @endif" id="family" role="tabpanel">
            <livewire:student.family-manager :student="$student" />
        </div>

        <div class="tab-pane fade @if ($tab == 'classes') active show @endif" id="classes" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0"><i class="ph-books me-2"></i>Danh sách lớp học</h5>
                </div>
                <div class="table-responsive">
                    <table class="table fs-table">
                        <thead>
                        <tr class="table-light">
                            <th style="width: 5%">STT</th>
                            <th style="width: 25%">Tên lớp</th>
                            <th style="width: 15%">Mã lớp</th>
                            <th style="width: 15%">Loại lớp</th>
                            <th style="width: 15%">Vai trò</th>
                            <th style="width: 15%">Trạng thái</th>
                            <th style="width: 10%">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($classes as $class)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1 + $classes->perPage() * ($classes->currentPage() - 1) }}</td>
                                <td style="word-break: break-word">
                                    <a href="{{ route('classes.show', $class->id) }}" class="fw-semibold">
                                        {{ $class->name }}
                                    </a>
                                </td>
                                <td style="word-break: break-word">{{ $class->code }}</td>
                                <td style="word-break: break-word">{{ $class->type->label() }}</td>
                                <td>
                                    <x-student-role-badge :role="\App\Enums\StudentRole::from($class->pivot->role)"/>
                                </td>
                                <td>
                                    <x-class-status-badge :status="$class->status"/>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('classes.show', $class->id) }}" class="text-body"
                                       data-bs-popup="tooltip" title="Xem chi tiết">
                                        <i class="ph-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Không có dữ liệu</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $classes->links('vendor.pagination.theme') }}
            </div>
        </div>

        <!-- Tab Yêu cầu chỉnh sửa thông tin -->
        <div class="tab-pane fade @if ($tab == 'update_requests') show active @endif" id="update_requests" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ph-clipboard-text me-2"></i>Yêu cầu chỉnh sửa thông tin</h5>

                    @if (Auth::user()->isStudent() && Auth::user()->id === $student->user_id)
                        <a href="{{ route('students.request-edit', $student->id) }}" class="btn btn-primary">
                            <i class="ph-plus me-1"></i> Tạo yêu cầu mới
                        </a>
                    @endif
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-light">
                                    <th width="5%">STT</th>
                                    <th width="20%">Ngày yêu cầu</th>
                                    <th width="15%">Trạng thái</th>
                                    <th width="10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($updateRequests as $index => $request)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 + ($updateRequests->currentPage() - 1) * $updateRequests->perPage() }}</td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="badge {{ $request->status->badgeColor() }}">{{ $request->status->label() }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('student-updates.show', $request->id) }}" class="btn btn-sm btn-info">
                                                <i class="ph-eye"></i> Xem
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Không có yêu cầu chỉnh sửa thông tin nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $updateRequests->links('vendor.pagination.theme') }}
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /right content -->
</div>
