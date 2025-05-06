<div>
    <div class="row">
        <div class="col-md-9 col-12">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bold">
                            <i class="ph-books"></i>
                            Thông tin lớp học
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label for="name" class="col-form-label">
                                        Tên lớp <span class="required text-danger">*</span>
                                    </label>
                                    <input wire:model="name" type="text" id="name"
                                           class="form-control @error('name') is-invalid @enderror" placeholder="Nhập tên lớp">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="code" class="col-form-label">
                                        Mã lớp <span class="required text-danger">*</span>
                                    </label>
                                    <input wire:model="code" type="text" id="code"
                                           class="form-control @error('code') is-invalid @enderror" placeholder="Nhập mã lớp">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label for="type" class="col-form-label">
                                        Loại lớp <span class="required text-danger">*</span>
                                    </label>
                                    <select wire:model="type" id="type"
                                           class="form-select @error('type') is-invalid @enderror">
                                        <option value="">-- Chọn loại lớp --</option>
                                        @foreach(\App\Enums\ClassType::getLabels() as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="status" class="col-form-label">
                                        Trạng thái <span class="required text-danger">*</span>
                                    </label>
                                    <select wire:model="status" id="status"
                                           class="form-select @error('status') is-invalid @enderror">
                                        <option value="">-- Chọn trạng thái --</option>
                                        @foreach(\App\Enums\Status::getLabels() as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label for="admission_year_id" class="col-form-label">
                                        Khóa học
                                    </label>
                                    <select wire:model="admission_year_id" id="admission_year_id"
                                           class="form-select @error('admission_year_id') is-invalid @enderror">
                                        <option value="">-- Chọn khóa học --</option>
                                        @foreach($admissionYears as $year)
                                            <option value="{{ $year['id'] }}">{{ $year['admission_year'] }} - {{ $year['school_year'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('admission_year_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label for="description" class="col-form-label">
                                        Mô tả <span class="required text-danger">*</span>
                                    </label>
                                    <textarea wire:model="description" id="description" rows="3"
                                              class="form-control @error('description') is-invalid @enderror" placeholder="Nhập mô tả"></textarea>
                                    @error('description')
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
                        <a href="{{ route('classes.show', $class->id) }}" type="button" class="btn btn-warning flex-fill fw-semibold"><i
                               class="ph-arrow-counter-clockwise fw-semibold"></i> Trở lại</a>
                        <button type="button" class="btn btn-danger flex-fill fw-semibold" wire:click="$dispatch('onOpenDeleteModal')">
                            <i class="ph-trash fw-semibold"></i> Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @script
        <script>
            window.addEventListener('onOpenDeleteModal', () => {
                new swal({
                    title: "Bạn có chắc chắn?",
                    text: "Dữ liệu sau khi xóa không thể phục hồi!",
                    showCancelButton: true,
                    confirmButtonColor: "#EE4444",
                    confirmButtonText: "Đồng ý!",
                    cancelButtonText: "Đóng!"
                }).then((value) => {
                    if (value.isConfirmed) {
                        Livewire.dispatch('deleteClass')
                    }
                })
            })
        </script>
    @endscript
</div>
