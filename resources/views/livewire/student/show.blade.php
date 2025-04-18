<div class="d-lg-flex align-items-lg-start">

    <!-- Left sidebar component -->
    <div class="bg-transparent shadow-none sidebar sidebar-component sidebar-expand-lg me-lg-3">

        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- Navigation -->
            <div class="card">
                <ul class="nav nav-sidebar" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#" class="nav-link @if ($tab == 'profile') active @endif" wire:click="setTab('profile')">
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
                        <a href="#" class="nav-link @if ($tab == 'classes') active @endif" wire:click="setTab('classes')">
                            <i class="mr-2 ph-books"></i>
                            Lớp học
                        </a>
                    </li>

                    <li class="nav-item-divider"></li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('students.edit', $student->id) }}" class="nav-link">
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

                <div class="card-header">
                    <h5 class="mb-0"><i class="ph-user me-2"></i>Thông tin sinh viên</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="pt-0 text-center">
                                <div class="mb-3 card-img-actions d-inline-block">
                                    <img class="img-fluid rounded-circle" src="{{ Avatar::create($student->fullName)->toBase64() }}" width="150" height="150" alt="">
                                </div>

                                <h6 class="mb-0">{{ $student->fullName }}</h6>
                                <span class="text-muted">Khoá {{ $student->admissionYear->admission_year }}</span>
                                @if ($editStatusMode)
                                    <div class="gap-2 d-flex align-items-center justify-content-center">
                                        <select class="form-select" wire:model.live="studentStatus">
                                            @foreach (\App\Enums\StudentStatus::cases() as $item)
                                                <option value="{{ $item->value }}" @if ($item->value == $student->status) selected @endif>{{ $item->getLabel() }}</option>
                                            @endforeach
                                        </select>
                                        <span class="cursor-pointer" wire:click="$set('editStatusMode', false)">
                                            <i class="mr-2 ph-x"></i>
                                        </span>
                                    </div>
                                @else
                                    <div class="gap-2 d-flex align-items-center justify-content-center">
                                        <x-student-status-badge :status="$student->status" />
                                        <span class="cursor-pointer" wire:click="$set('editStatusMode', true)">
                                            <i class="mr-2 ph-note-pencil"></i>
                                        </span>
                                    </div>
                                @endif

                            </div>

                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-6 col-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Thông tin cơ bản</h6>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="40%">Họ và tên</th>
                                                <td>{{ $student->fullName }}</td>
                                            </tr>
                                            <tr>
                                                <th>Mã sinh viên</th>
                                                <td>{{ $student->code }}</td>
                                            </tr>
                                            <tr>
                                                <th>Ngày sinh</th>
                                                <td>{{ $student->dob ? \Illuminate\Support\Carbon::make($student->dob)->format('d/m/Y') : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Giới tính</th>
                                                <td>{{ $student->gender === 'male' ? 'Nam' : 'Nữ' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $student->email ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Số điện thoại</th>
                                                <td>{{ $student->phone ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>CCCD/CMND</th>
                                                <td>{{ $student->citizen_identification ?: 'N/A' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3">Thông tin chi tiết</h6>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="40%">Nơi sinh</th>
                                                <td>{{ $student->pob ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Địa chỉ hiện tại</th>
                                                <td>{{ $student->address ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Quê quán</th>
                                                <td>{{ $student->countryside ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Dân tộc</th>
                                                <td>{{ $student->ethnic ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tôn giáo</th>
                                                <td>{{ $student->religion ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Quốc tịch</th>
                                                <td>{{ $student->nationality ?: 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Đối tượng chính sách</th>
                                                <td>{{ $student->social_policy_object ?: 'N/A' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if($student->note)
                                <div class="mt-3">
                                    <h6 class="mb-2">Ghi chú</h6>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            {{ $student->note }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade @if ($tab == 'family') active show @endif" id="family" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0"><i class="ph-users-three me-2"></i>Thông tin sinh viên</h5>
                </div>
                @if($families->isEmpty())
                    <div class="alert alert-info">
                        <i class="ph-info me-2"></i> Chưa có thông tin gia đình.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table fs-table">
                            <thead>
                            <tr class="table-light">
                                <th width="5%">STT</th>
                                <th width="20%">Mối quan hệ</th>
                                <th width="30%">Họ và tên</th>
                                <th width="25%">Nghề nghiệp</th>
                                <th width="20%">Số điện thoại</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($families as $index => $family)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <x-family-relationship-badge :relationship="$family->relationship" />
                                    </td>
                                    <td>{{ $family->full_name ?: 'N/A' }}</td>
                                    <td>{{ $family->job ?: 'N/A' }}</td>
                                    <td>{{ $family->phone ?: 'N/A' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
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
                            <th width="5%">STT</th>
                            <th width="25%">Tên lớp</th>
                            <th width="15%">Mã lớp</th>
                            <th width="15%">Loại lớp</th>
                            <th width="15%">Vai trò</th>
                            <th width="15%">Trạng thái</th>
                            <th width="10%">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($classes as $class)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1 + $classes->perPage() * ($classes->currentPage() - 1) }}</td>
                                <td>
                                    <a href="{{ route('classes.show', $class->id) }}" class="fw-semibold">
                                        {{ $class->name }}
                                    </a>
                                </td>
                                <td>{{ $class->code }}</td>
                                <td>{{ $class->type->label() }}</td>
                                <td>
                                    <x-student-role-badge :role="\App\Enums\StudentRole::from($class->pivot->role)" />
                                </td>
                                <td>
                                    <x-class-status-badge :status="$class->status" />
                                </td>
                                <td>
                                    <a href="{{ route('classes.show', $class->id) }}" class="text-body" data-bs-popup="tooltip" title="Xem chi tiết">
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

    </div>
    <!-- /right content -->

</div>
