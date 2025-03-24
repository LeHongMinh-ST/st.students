<div>
    <div class="border-0 shadow-lg card">
        <div class="card-body">
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-bold">Chọn file để tải lên</label>
                    <div class="p-4 text-center dropzone d-block" id="dropzoneFile" wire:ignore.self>
                        <div class="mt-4">
                            <i class="ph-cloud-arrow-up display-4"></i>
                        </div>
                        <div>
                            <p class="text-muted">{{ $fileName }}</p>
                            <input type="file" name="file" wire:model.live="file" class="d-none" id="fileInput" accept=".xls,.xlsx,.csv">
                        </div>
                    </div>
                </div>

                @if ($file)
                    <div class="text-center">
                        <button type="submit" class="mt-3 btn btn-primary">
                            <span id="btnText">Tải lên</span>
                            <span id="loadingText" class="d-none">Đang tải...</span>
                        </button>
                    </div>
                @endif
            </form>

            <div id="uploadMessage" class="mt-3 alert d-none"></div>
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
    </script>
@endscript
