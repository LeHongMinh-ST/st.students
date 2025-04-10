<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Thông tin lớp học</h5>
        </div>
        <div class="card-body">
            <form wire:submit="save">
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Tên lớp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name" placeholder="Nhập tên lớp">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Mã lớp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" wire:model="code" placeholder="Nhập mã lớp">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Loại lớp <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" wire:model="type">
                                <option value="">-- Chọn loại lớp --</option>
                                @foreach($classTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                                <option value="">-- Chọn trạng thái --</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}">{{ $status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Khóa học</label>
                            <select class="form-select @error('admission_year_id') is-invalid @enderror" wire:model="admission_year_id">
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
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả <span class="text-danger">*</span></label>
                    <textarea rows="3" class="form-control @error('description') is-invalid @enderror" wire:model="description" placeholder="Nhập mô tả"></textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <a href="{{ route('classes.index') }}" class="btn btn-light">Hủy</a>
                    <button type="submit" class="btn btn-primary">Lưu <i class="ph-floppy-disk ms-2"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
