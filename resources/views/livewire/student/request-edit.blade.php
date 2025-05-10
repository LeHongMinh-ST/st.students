<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="ph-user-circle me-2"></i>Yêu cầu chỉnh sửa thông tin cá nhân</h5>
        </div>

        <div class="card-body">
            @if ($hasPendingRequest)
                <div class="alert alert-info">
                    <h6 class="alert-heading fw-bold">Bạn đã có một yêu cầu chỉnh sửa đang chờ xử lý</h6>
                    <p>Trạng thái: <span class="badge {{ $pendingRequest->status->badgeColor() }}">{{ $pendingRequest->status->label() }}</span></p>
                    <p>Ngày tạo: {{ $pendingRequest->created_at->format('d/m/Y H:i') }}</p>
                    <p>Vui lòng đợi yêu cầu được duyệt hoặc từ chối trước khi tạo yêu cầu mới.</p>

                    <div class="mt-3">
                        <h6 class="fw-bold">Thông tin đã yêu cầu thay đổi:</h6>
                        @php
                            $changes = json_decode($pendingRequest->change_column, true) ?? [];
                        @endphp

                        @if (count($changes) > 0)
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
                                        @foreach ($changes as $field => $change)
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
                </div>
            @else
                <form wire:submit="submitRequest">
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Số điện thoại:</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" wire:model="phone">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">CCCD/CMND:</label>
                                <input type="text" class="form-control @error('citizen_identification') is-invalid @enderror" wire:model="citizen_identification">
                                @error('citizen_identification')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Nơi sinh:</label>
                                <input type="text" class="form-control @error('pob') is-invalid @enderror" wire:model="pob">
                                @error('pob')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ:</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" wire:model="address">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Hộ khẩu thường trú:</label>
                                <input type="text" class="form-control @error('permanent_residence') is-invalid @enderror" wire:model="permanent_residence">
                                @error('permanent_residence')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Quê quán:</label>
                                <input type="text" class="form-control @error('countryside') is-invalid @enderror" wire:model="countryside">
                                @error('countryside')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Quốc tịch:</label>
                                <input type="text" class="form-control @error('nationality') is-invalid @enderror" wire:model="nationality">
                                @error('nationality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Dân tộc:</label>
                                <input type="text" class="form-control @error('ethnic') is-invalid @enderror" wire:model="ethnic">
                                @error('ethnic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Tôn giáo:</label>
                                <input type="text" class="form-control @error('religion') is-invalid @enderror" wire:model="religion">
                                @error('religion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Tôn giáo:</label>
                                <input type="text" class="form-control @error('religion') is-invalid @enderror" wire:model="religion">
                                @error('religion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Loại đào tạo:</label>
                                <select class="form-select @error('training_type') is-invalid @enderror" wire:model="training_type">
                                    @foreach (\App\Enums\TrainingType::cases() as $type)
                                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                    @endforeach
                                </select>
                                @error('training_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Đối tượng chính sách:</label>
                                <select class="form-select @error('social_policy_object') is-invalid @enderror" wire:model="social_policy_object">
                                    @foreach (\App\Enums\SocialPolicyObject::cases() as $object)
                                        <option value="{{ $object->value }}">{{ $object->label() }}</option>
                                    @endforeach
                                </select>
                                @error('social_policy_object')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Ảnh đại diện:</label>
                                <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" wire:model="thumbnail">
                                @error('thumbnail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if ($thumbnail)
                                    <div class="mt-2">
                                        <img src="{{ $thumbnail->temporaryUrl() }}" class="img-thumbnail" width="100">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Ghi chú:</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" wire:model="note" rows="3"></textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="ph-info me-2"></i> Lưu ý: Yêu cầu chỉnh sửa thông tin sẽ được gửi đến lớp trưởng, giáo viên chủ nhiệm và cán bộ quản lý để duyệt. Thông tin chỉ được cập nhật sau khi yêu cầu được duyệt hoàn tất.
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ph-paper-plane-tilt me-2"></i>
                            Gửi yêu cầu chỉnh sửa
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
