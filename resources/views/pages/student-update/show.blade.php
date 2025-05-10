<x-admin-layout>
    <x-slot name="header">
        <div class="shadow page-header page-header-light">
            <div class="page-header-content d-lg-flex">
                <div class="d-flex">
                    <h4 class="mb-0 page-title">
                        Chi tiết yêu cầu chỉnh sửa thông tin - {{ $update->student->full_name }} - {{ $update->student->code }}
                    </h4>

                    <a href="#page_header" class="p-0 border-transparent btn btn-light align-self-center collapsed d-lg-none rounded-pill ms-auto" data-bs-toggle="collapse">
                        <i class="m-1 ph-caret-down collapsible-indicator ph-sm"></i>
                    </a>
                </div>

            </div>

            <div class="page-header-content d-lg-flex border-top">
                <div class="d-flex">
                    <div class="py-2 breadcrumb">
                        <a href="{{ route('dashboard') }}" class="breadcrumb-item"><i class="ph-house"></i></a>
                        <a href="{{ route('student-updates.index') }}" class="breadcrumb-item">Quản lý yêu cầu chỉnh sửa thông tin</a>
                        <span class="breadcrumb-item active">Chi tiết yêu cầu</span>
                    </div>

                    <a href="#breadcrumb_elements" class="p-0 border-transparent btn btn-light align-self-center collapsed d-lg-none rounded-pill ms-auto" data-bs-toggle="collapse">
                        <i class="m-1 ph-caret-down collapsible-indicator ph-sm"></i>
                    </a>
                </div>

            </div>
        </div>
    </x-slot>


    <div class="content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="ph-clipboard-text me-2"></i>Chi tiết yêu cầu chỉnh sửa thông tin</h5>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <h6>Thông tin sinh viên:</h6>
                    <p><strong>Họ tên:</strong> {{ $update->student->full_name }}</p>
                    <p><strong>Mã sinh viên:</strong> {{ $update->student->code }}</p>
                    <p><strong>Ngày yêu cầu:</strong> {{ $update->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Trạng thái:</strong> <span class="badge {{ $update->status->badgeColor() }}">{{ $update->status->label() }}</span></p>
                </div>

                <div class="mb-3">
                    <h6>Thông tin thay đổi:</h6>
                    @php
                        $changes = json_decode($update->change_column, true) ?? [];
                    @endphp

                    @if(count($changes) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Trường thông tin</th>
                                        <th>Giá trị cũ</th>
                                        <th>Giá trị mới</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($changes as $field => $change)
                                        <tr>
                                            <td>
                                                @switch($field)
                                                    @case('pob')
                                                        Nơi sinh
                                                        @break
                                                    @case('address')
                                                        Địa chỉ
                                                        @break
                                                    @case('permanent_residence')
                                                        Hộ khẩu thường trú
                                                        @break
                                                    @case('countryside')
                                                        Quê quán
                                                        @break
                                                    @case('training_type')
                                                        Loại đào tạo
                                                        @break
                                                    @case('phone')
                                                        Số điện thoại
                                                        @break
                                                    @case('nationality')
                                                        Quốc tịch
                                                        @break
                                                    @case('citizen_identification')
                                                        CCCD/CMND
                                                        @break
                                                    @case('ethnic')
                                                        Dân tộc
                                                        @break
                                                    @case('religion')
                                                        Tôn giáo
                                                        @break
                                                    @case('social_policy_object')
                                                        Đối tượng chính sách
                                                        @break
                                                    @case('note')
                                                        Ghi chú
                                                        @break
                                                    @case('email')
                                                        Email
                                                        @break
                                                    @case('thumbnail')
                                                        Ảnh đại diện
                                                        @break
                                                    @default
                                                        {{ $field }}
                                                @endswitch
                                            </td>
                                            <td>{{ $change['old'] ?? '' }}</td>
                                            <td>{{ $change['new'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>Không có thông tin thay đổi.</p>
                    @endif
                </div>

                <div class="mb-3">
                    <h6>Lịch sử phê duyệt:</h6>
                    @php
                        $approvals = \App\Models\ApproveStudentUpdates::where('student_info_updates_id', $update->id)
                            ->orderBy('created_at')
                            ->get();
                    @endphp

                    @if($approvals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>Người duyệt</th>
                                        <th>Trạng thái</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approvals as $approval)
                                        <tr>
                                            <td>{{ $approval->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($approval->approveable_type === \App\Models\Student::class)
                                                    @php
                                                        $student = \App\Models\Student::find($approval->approveable_id);
                                                    @endphp
                                                    {{ $student ? $student->full_name . ' (Lớp trưởng)' : 'N/A' }}
                                                @elseif($approval->approveable_type === \App\Models\User::class)
                                                    @php
                                                        $user = \App\Models\User::find($approval->approveable_id);
                                                    @endphp
                                                    {{ $user ? $user->full_name : 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $approval->status->badgeColor() }}">{{ $approval->status->label() }}</span>
                                            </td>
                                            <td>{{ $approval->note ?: 'Không có ghi chú' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>Chưa có lịch sử phê duyệt.</p>
                    @endif
                </div>

                <div class="text-end">
                    <a href="{{ route('student-updates.index') }}" class="btn btn-secondary">
                        <i class="ph-arrow-left me-1"></i> Quay lại
                    </a>

                    @if(Auth::user()->isSuperAdmin())
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="ph-x-circle me-1"></i> Từ chối
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approveModal">
                        <i class="ph-check-circle me-1"></i> Duyệt
                    </button>
                    @elseif($update->status === \App\Enums\StudentUpdateStatus::Pending && Auth::user()->can('approveAsClassMonitor', $update))
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="ph-x-circle me-1"></i> Từ chối
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approveModal">
                        <i class="ph-check-circle me-1"></i> Duyệt
                    </button>
                    @elseif($update->status === \App\Enums\StudentUpdateStatus::ClassOfficerApproved && Auth::user()->can('approveAsTeacher', $update))
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="ph-x-circle me-1"></i> Từ chối
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approveModal">
                        <i class="ph-check-circle me-1"></i> Duyệt
                    </button>
                    @elseif($update->status === \App\Enums\StudentUpdateStatus::TeacherApproved && Auth::user()->can('approveAsAdmin', $update))
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="ph-x-circle me-1"></i> Từ chối
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approveModal">
                        <i class="ph-check-circle me-1"></i> Duyệt
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Approve -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Xác nhận duyệt yêu cầu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('student-updates.approve', $update->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn duyệt yêu cầu chỉnh sửa thông tin này?</p>
                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú (tùy chọn):</label>
                            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Duyệt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Xác nhận từ chối yêu cầu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('student-updates.reject', $update->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn từ chối yêu cầu chỉnh sửa thông tin này?</p>
                        <div class="mb-3">
                            <label for="note" class="form-label">Lý do từ chối (bắt buộc):</label>
                            <textarea class="form-control" id="note" name="note" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger">Từ chối</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
