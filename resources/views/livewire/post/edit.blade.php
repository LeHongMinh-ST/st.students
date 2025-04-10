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
                        <a href="{{ route('posts.index') }}" type="button" class="btn btn-warning flex-fill fw-semibold"><i
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
                        Livewire.dispatch('deletePost')
                    }
                })
            })
        </script>
    @endscript
</div>
