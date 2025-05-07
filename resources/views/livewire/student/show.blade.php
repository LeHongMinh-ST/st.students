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
                        <div class="col-lg-3 col-md-4 col-sm-12 col-12 mb-3 mb-sm-4">
                            <div class="pt-0 text-center">
                                <div class="mb-3 card-img-actions d-inline-block">
                                    <img class="img-fluid rounded-circle"
                                         src="{{ Avatar::create($student->fullName)->toBase64() }}" width="150"
                                         height="150" alt="">
                                </div>

                                <h6 class="mb-0">{{ $student->fullName }}</h6>
                                <span class="text-muted">Khoá {{ $student->admissionYear->admission_year }}</span>
                                @if ($editStatusMode)
                                    <div class="gap-2 d-flex align-items-center justify-content-center mt-2">
                                        <select class="form-select" wire:model.live="studentStatus">
                                            @foreach (\App\Enums\StudentStatus::cases() as $item)
                                                <option value="{{ $item->value }}"
                                                        @if ($item->value == $student->status) selected @endif>{{ $item->getLabel() }}</option>
                                            @endforeach
                                        </select>
                                        <span class="cursor-pointer" wire:click="$set('editStatusMode', false)">
                                            <i class="mr-2 ph-x"></i>
                                        </span>
                                    </div>
                                @else
                                    <div class="gap-2 d-flex align-items-center justify-content-center mt-2">
                                        <x-student-status-badge :status="$student->status"/>
                                        <span class="cursor-pointer" wire:click="$set('editStatusMode', true)">
                                            <i class="mr-2 ph-note-pencil"></i>
                                        </span>
                                    </div>
                                @endif

                            </div>

                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-12 col-12">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Thông tin sinh viên</h6>
                                        <a href="{{ route('students.edit-detail', $student->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="ph-pencil me-1"></i> Chỉnh sửa
                                        </a>
                                    </div>

                                    @if ($editInfoMode)
                                        <!-- Form chỉnh sửa thông tin sinh viên -->
                                        <div class="row">
                                            <div class="col-md-9 col-12">
                                                <div class="card">
                                                    <div class="card-header bold">
                                                        <i class="ph-student"></i>
                                                        Chỉnh sửa thông tin sinh viên
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row mb-3">
                                                            <div class="col-lg-6">
                                                                <label for="email" class="col-form-label">
                                                                    Email
                                                                </label>
                                                                <input wire:model="email" type="email" id="email"
                                                                       class="form-control @error('email') is-invalid @enderror"
                                                                       placeholder="Nhập email">
                                                                @error('email')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <label for="phone" class="col-form-label">
                                                                    Số điện thoại
                                                                </label>
                                                                <input wire:model="phone" type="text" id="phone"
                                                                       class="form-control @error('phone') is-invalid @enderror"
                                                                       placeholder="Nhập số điện thoại">
                                                                @error('phone')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-6">
                                                                <label for="citizen_identification"
                                                                       class="col-form-label">
                                                                    CCCD/CMND
                                                                </label>
                                                                <input wire:model="citizen_identification" type="text"
                                                                       id="citizen_identification"
                                                                       class="form-control @error('citizen_identification') is-invalid @enderror"
                                                                       placeholder="Nhập CCCD/CMND">
                                                                @error('citizen_identification')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <label for="training_type" class="col-form-label">
                                                                    Loại đào tạo
                                                                </label>
                                                                <select wire:model="training_type" id="training_type"
                                                                        class="form-select @error('training_type') is-invalid @enderror"
                                                                        disabled>
                                                                    @foreach (\App\Enums\TrainingType::cases() as $item)
                                                                        <option
                                                                            value="{{ $item->value }}">{{ $item->label() }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('training_type')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-6">
                                                                <label for="nationality" class="col-form-label">
                                                                    Quốc tịch
                                                                </label>
                                                                <input wire:model="nationality" type="text"
                                                                       id="nationality"
                                                                       class="form-control @error('nationality') is-invalid @enderror"
                                                                       placeholder="Nhập quốc tịch">
                                                                @error('nationality')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <label for="ethnic" class="col-form-label">
                                                                    Dân tộc
                                                                </label>
                                                                <input wire:model="ethnic" type="text" id="ethnic"
                                                                       class="form-control @error('ethnic') is-invalid @enderror"
                                                                       placeholder="Nhập dân tộc">
                                                                @error('ethnic')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-6">
                                                                <label for="religion" class="col-form-label">
                                                                    Tôn giáo
                                                                </label>
                                                                <input wire:model="religion" type="text" id="religion"
                                                                       class="form-control @error('religion') is-invalid @enderror"
                                                                       placeholder="Nhập tôn giáo">
                                                                @error('religion')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <label for="pob" class="col-form-label">
                                                                    Nơi sinh
                                                                </label>
                                                                <input wire:model="pob" type="text" id="pob"
                                                                       class="form-control @error('pob') is-invalid @enderror"
                                                                       placeholder="Nhập nơi sinh">
                                                                @error('pob')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-6">
                                                                <label for="address" class="col-form-label">
                                                                    Địa chỉ hiện tại
                                                                </label>
                                                                <input wire:model="address" type="text" id="address"
                                                                       class="form-control @error('address') is-invalid @enderror"
                                                                       placeholder="Nhập địa chỉ hiện tại">
                                                                @error('address')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <label for="countryside" class="col-form-label">
                                                                    Quê quán
                                                                </label>
                                                                <input wire:model="countryside" type="text"
                                                                       id="countryside"
                                                                       class="form-control @error('countryside') is-invalid @enderror"
                                                                       placeholder="Nhập quê quán">
                                                                @error('countryside')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-6">
                                                                <label for="social_policy_object"
                                                                       class="col-form-label">
                                                                    Đối tượng chính sách
                                                                </label>
                                                                <select wire:model="social_policy_object"
                                                                        id="social_policy_object"
                                                                        class="form-select @error('social_policy_object') is-invalid @enderror">
                                                                    @foreach (\App\Enums\SocialPolicyObject::cases() as $item)
                                                                        <option
                                                                            value="{{ $item->value }}">{{ $item->label() }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('social_policy_object')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col">
                                                                <label for="note" class="col-form-label">
                                                                    Ghi chú
                                                                </label>
                                                                <textarea wire:model="note" id="note" rows="3"
                                                                          class="form-control @error('note') is-invalid @enderror"
                                                                          placeholder="Nhập ghi chú"></textarea>
                                                                @error('note')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="card">
                                                    <div class="card-header bold">
                                                        Thông tin cơ bản
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="text-center mb-3">
                                                            <img
                                                                src="{{ Avatar::create($student->fullName)->toBase64() }}"
                                                                class="img-fluid rounded-circle"
                                                                style="max-width: 150px;">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Họ và tên:</label>
                                                            <div>{{ $student->fullName }}</div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Mã sinh viên:</label>
                                                            <div>{{ $student->code }}</div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Ngày sinh:</label>
                                                            <div>{{ $student->dob ? \Illuminate\Support\Carbon::make($student->dob)->format('d/m/Y') : 'N/A' }}</div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Giới tính:</label>
                                                            <div>{{ $student->gender->value == 'male' ? 'Nam' : 'Nữ' }}</div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Trạng thái:</label>
                                                            <div>
                                                                <x-student-status-badge :status="$student->status"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card mt-3">
                                                    <div class="card-header bold">
                                                        Hành động
                                                    </div>
                                                    <div
                                                        class="gap-2 card-body d-flex flex-column justify-content-center">
                                                        <div class="d-flex gap-2">
                                                            <button wire:loading wire:target="saveInfo"
                                                                    class="shadow btn btn-primary fw-semibold flex-fill">
                                                                <i class="ph-circle-notch spinner fw-semibold"></i>
                                                                Lưu
                                                            </button>
                                                            <button wire:click="saveInfo" wire:loading.remove
                                                                    class="shadow btn btn-primary fw-semibold flex-fill">
                                                                <i class="ph-floppy-disk fw-semibold"></i>
                                                                Lưu
                                                            </button>
                                                        </div>
                                                        <div class="d-flex gap-2 mt-2">
                                                            <button wire:click="cancelEdit" type="button"
                                                                    class="btn btn-warning flex-fill fw-semibold">
                                                                <i class="ph-arrow-counter-clockwise fw-semibold"></i>
                                                                Hủy
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
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
                                    @endif
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

    </div>
    <!-- /right content -->
</div>
