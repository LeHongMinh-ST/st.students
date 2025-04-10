<div>
    <div class="row">
        <div class="col-md-9 col-12">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bold">
                            <i class="ph-note"></i>
                            Thông tin bài viết
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="title" class="col-form-label">
                                        Tiêu đề <span class="required text-danger">*</span>
                                    </label>
                                    <input wire:model="title" type="text" id="title"
                                           class="form-control @error('title') is-invalid @enderror" placeholder="Nhập tiêu đề">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label for="status" class="col-form-label">
                                        Trạng thái <span class="required text-danger">*</span>
                                    </label>
                                    <select wire:model="status" id="status"
                                           class="form-select @error('status') is-invalid @enderror">
                                        <option value="">-- Chọn trạng thái --</option>
                                        @foreach(\App\Enums\PostStatus::getLabels() as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label for="content" class="col-form-label">
                                        Nội dung <span class="required text-danger">*</span>
                                    </label>
                                    <x-ckeditor id="content" name="content" wire:model="content" />
                                    @error('content')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
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
                    <a href="{{ route('posts.index') }}" type="button" class="btn btn-warning flex-fill fw-semibold"><i
                           class="ph-arrow-counter-clockwise fw-semibold"></i> Trở lại</a>
                </div>
            </div>
        </div>
    </div>
</div>
