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
                        <a href="{{ route('students.index', ['admission_year' => $update->student->admissionYear->admission_year]) }}" class="breadcrumb-item">Sinh viên</a>
                        <a href="{{ route('students.show', $update->student->id) }}" class="breadcrumb-item">{{ $update->student->full_name }} - {{ $update->student->code }}</a>
                        <span class="breadcrumb-item active">Chi tiết yêu cầu chỉnh sửa thông tin</span>
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
                    <a href="{{ route('students.show', $update->student->id) }}" class="btn btn-secondary">
                        <i class="ph-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
