<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Chỉnh sửa phản ánh</h5>
        </div>

        <div class="card-body">
            <form wire:submit="save">
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" id="title" class="form-control @error('title') is-invalid @enderror" wire:model="title">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
                    <textarea id="content" class="form-control @error('content') is-invalid @enderror" wire:model="content" rows="5"></textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <a href="{{ route('feedbacks.show', $feedback->id) }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>