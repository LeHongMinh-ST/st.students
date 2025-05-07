<div>
    <div class="row">
        <div class="col-md-9 col-12">
            <div class="row">
                <div class="col">
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
                                           class="form-control @error('email') is-invalid @enderror" placeholder="Nhập email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="phone" class="col-form-label">
                                        Số điện thoại
                                    </label>
                                    <input wire:model="phone" type="text" id="phone"
                                           class="form-control @error('phone') is-invalid @enderror" placeholder="Nhập số điện thoại">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label for="citizen_identification" class="col-form-label">
                                        CCCD/CMND
                                    </label>
                                    <input wire:model="citizen_identification" type="text" id="citizen_identification"
                                           class="form-control @error('citizen_identification') is-invalid @enderror" placeholder="Nhập CCCD/CMND">
                                    @error('citizen_identification')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="training_type" class="col-form-label">
                                        Loại đào tạo
                                    </label>
                                    <select wire:model="training_type" id="training_type" class="form-select @error('training_type') is-invalid @enderror" disabled>
                                        @foreach (\App\Enums\TrainingType::cases() as $item)
                                            <option value="{{ $item->value }}">{{ $item->label() }}</option>
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
                                    <input wire:model="nationality" type="text" id="nationality"
                                           class="form-control @error('nationality') is-invalid @enderror" placeholder="Nhập quốc tịch">
                                    @error('nationality')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="ethnic" class="col-form-label">
                                        Dân tộc
                                    </label>
                                    <input wire:model="ethnic" type="text" id="ethnic"
                                           class="form-control @error('ethnic') is-invalid @enderror" placeholder="Nhập dân tộc">
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
                                           class="form-control @error('religion') is-invalid @enderror" placeholder="Nhập tôn giáo">
                                    @error('religion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="pob" class="col-form-label">
                                        Nơi sinh
                                    </label>
                                    <input wire:model="pob" type="text" id="pob"
                                           class="form-control @error('pob') is-invalid @enderror" placeholder="Nhập nơi sinh">
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
                                           class="form-control @error('address') is-invalid @enderror" placeholder="Nhập địa chỉ hiện tại">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="countryside" class="col-form-label">
                                        Quê quán
                                    </label>
                                    <input wire:model="countryside" type="text" id="countryside"
                                           class="form-control @error('countryside') is-invalid @enderror" placeholder="Nhập quê quán">
                                    @error('countryside')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label for="social_policy_object" class="col-form-label">
                                        Đối tượng chính sách
                                    </label>
                                    <select wire:model="social_policy_object" id="social_policy_object" class="form-select @error('social_policy_object') is-invalid @enderror">
                                        @foreach (\App\Enums\SocialPolicyObject::cases() as $item)
                                            <option value="{{ $item->value }}">{{ $item->label() }}</option>
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
                                              class="form-control @error('note') is-invalid @enderror" placeholder="Nhập ghi chú"></textarea>
                                    @error('note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
                        <img src="{{ Avatar::create($student->fullName)->toBase64() }}" class="img-fluid rounded-circle" style="max-width: 150px;">
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
                        <div>{{ $student->gender->value === 'male' ? 'Nam' : 'Nữ' }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Trạng thái:</label>
                        <div>
                            <x-student-status-badge :status="$student->status" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bold">
                    Hành động
                </div>
                <div class="gap-2 card-body d-flex flex-column justify-content-center">
                    <div class="d-flex gap-2">
                        <button wire:loading wire:target="save" class="shadow btn btn-primary fw-semibold flex-fill">
                            <i class="ph-circle-notch spinner fw-semibold"></i>
                            Lưu
                        </button>
                        <button wire:click="save" wire:loading.remove class="shadow btn btn-primary fw-semibold flex-fill">
                            <i class="ph-floppy-disk fw-semibold"></i>
                            Lưu
                        </button>
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <button wire:click="cancel" type="button" class="btn btn-warning flex-fill fw-semibold">
                            <i class="ph-arrow-counter-clockwise fw-semibold"></i> Trở lại
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
