<div>
    <form id="uploadForm" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label fw-bold"><i class="mr-1 ph-file-arrow-up"></i>Chọn tệp để tải lên</label>
            <div class="p-4 text-center dropzone d-block" id="dropzoneFile" wire:ignore.self>
                <div class="mt-4">
                    <i class="ph-cloud-arrow-up display-4"></i>
                </div>
                <div>
                    <p class="text-muted">{{ $fileName }}</p>
                    <input type="file" name="file" wire:model.live="file" class="d-none" id="fileInput" accept=".xls,.xlsx,.csv">
                </div>
                <div wire:loading wire:target="file" class="text-center w-100">
                    <span class="spinner-border spinner-border-sm"></span> Đang đọc dữ liệu...
                </div>
            </div>
        </div>

        <!-- Nút tải file mẫu -->
        <div class="mb-2">
            <a href="{{ route('file.download-template', ['name' => $tempFile]) }}">
                <i class="ph-download-simple me-1"></i> Tải tệp mẫu
            </a>
        </div>

        @if ($file)
            <div class="text-center" wire:transition>
                <button type="button" class="mt-3 btn btn-primary disabled" wire:loading wire:target="import">
                    <span class="spinner-border spinner-border-sm"></span> Tải lên
                </button>
                <button type="button" class="mt-3 btn btn-primary" wire:loading.remove wire:target="import" wire:click="import">
                    <i class="ph-cloud-arrow-up me-1"></i> Tải lên
                </button>

                <button type="button" wire:loading wire:target="resetFile" class="mt-3 btn btn-danger disabled">
                    <span class="spinner-border spinner-border-sm"></span> Huỷ
                </button>
                <button type="button" class="mt-3 btn btn-danger" wire:loading.remove wire:target="resetFile" wire:click="resetFile">
                    <i class="ph-x me-1"></i> Huỷ
                </button>
            </div>
        @endif
    </form>

    <div id="model-process" wire:ignore.self class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import dữ liệu</h5>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            {{ $fileName }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped @if (!$importCompleted) progress-bar-animated @endif" style="width: {{ $importProgress }}%" aria-valuenow="{{ $importProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 row">
                        <div class="col">
                            <div>Tổng số bản ghi: {{ $importTotal }}</div>
                            <div>Bản ghi thành công: {{ $importSuccessCount }}</div>
                            <div>Bản ghi bị lỗi: {{ $importErrorCount }}</div>
                        </div>
                    </div>

                    @if ($importCompleted)
                        <div class="mt-2 row">
                            <div class="col">
                                <button type="button" class="btn btn-primary" wire:click="closeProcessModal">Đóng</button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        $(document).ready(function() {
            let dropzone = $("#dropzoneFile");
            let fileInput = $("#fileInput");

            dropzone.on("click", function(e) {
                if (!$(e.target).is("#fileInput")) {
                    fileInput.trigger("click");
                }
            });

            $(document).on("dragover drop", function(e) {
                e.preventDefault();
                e.stopPropagation();
            });

            dropzone.on("dragover", function(e) {
                e.preventDefault();
                dropzone.addClass("border-primary");
            });

            dropzone.on("dragleave", function() {
                dropzone.removeClass("border-primary");
            });


            dropzone.on("drop", function(e) {
                e.preventDefault();
                dropzone.removeClass("border-primary");

                let files = e.originalEvent.dataTransfer.files;
                if (files.length) {
                    let file = files[0];
                    @this.upload('file', file);
                }
            });

        });

        window.addEventListener('onOpenProcessModal', () => {
            $('#model-process').modal('show')
        })

        window.addEventListener('onCloseProcessModal', () => {
            $('#model-process').modal('hide')
        })
    </script>
@endscript
