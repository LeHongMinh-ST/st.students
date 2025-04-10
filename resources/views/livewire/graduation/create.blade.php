<div>
    <div class="row">
        <div class="col-md-9 col-12">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bold">
                            <i class="ph-graduation-cap"></i>
                            Thông tin đợt tốt nghiệp
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label for="name" class="col-form-label">
                                        Tên đợt tốt nghiệp <span class="required text-danger">*</span>
                                    </label>
                                    <input wire:model="name" type="text" id="name"
                                           class="form-control @error('name') is-invalid @enderror" placeholder="Nhập tên đợt tốt nghiệp">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="school_year" class="col-form-label">
                                        Năm học <span class="required text-danger">*</span>
                                    </label>
                                    <input wire:model="school_year" type="text" id="school_year"
                                           class="form-control @error('school_year') is-invalid @enderror" placeholder="Nhập năm học (VD: 2023-2024)">
                                    @error('school_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label for="certification" class="col-form-label">
                                        Số quyết định <span class="required text-danger">*</span>
                                    </label>
                                    <input wire:model="certification" type="text" id="certification"
                                           class="form-control @error('certification') is-invalid @enderror" placeholder="Nhập số quyết định">
                                    @error('certification')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="certification_date" class="col-form-label">
                                        Ngày quyết định <span class="required text-danger">*</span>
                                    </label>
                                    <input wire:model="certification_date" type="date" id="certification_date"
                                           class="form-control @error('certification_date') is-invalid @enderror">
                                    @error('certification_date')
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
                <div class="gap-2 card-body d-flex justify-content-center">
                    <button wire:loading wire:target="save" class="shadow btn btn-primary fw-semibold flex-fill">
                        <i class="ph-circle-notch spinner fw-semibold"></i>
                        Lưu
                    </button>
                    <button wire:click="save" wire:loading.remove class="shadow btn btn-primary fw-semibold flex-fill">
                        <i class="ph-floppy-disk fw-semibold"></i>
                        Lưu
                    </button>
                    <a href="{{ route('graduation.index') }}" type="button" class="btn btn-warning flex-fill fw-semibold"><i
                           class="ph-arrow-counter-clockwise fw-semibold"></i> Trở lại</a>
                </div>
            </div>
        </div>
    </div>
</div>
