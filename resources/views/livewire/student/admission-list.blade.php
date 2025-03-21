<div class="list-admissions">
    <div class="row">
        @foreach ($admissionYears as $item)
            <div class="col-lg-4 col-sm-6 col-12">
                <div class="card cursor-poiter" wire:click="setAdmissionYear({{ $item->admission_year }})">
                    <div class="card-body d-flex align-items-start">
                        <a href="#" class="p-2 bg-opacity-10 bg-primary text-primary rounded-pill me-3">
                            <i class="ph-notebook"></i>
                        </a>

                        <div class="flex-fill">
                            <h6 class="mb-1 fw-semibold"><span href="#" class="text-body">Khóa {{ $item->admission_year }}</span></h6>
                            <div><span class="fw-semibold">Nhập học: </span>{{ $item->students_count }} sinh viên</div>
                            <div><span class="fw-semibold">Hiện tại: </span>{{ $item->currently_studying_count }} sinh viên</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach


    </div>
    @if ($admissionYears->hasMorePages())
        <div class="mt-4 text-center">
            <button wire:loading wire:target="loadMore" class="shadow btn btn-primary fw-semibold flex-fill">
                <i class="ph-circle-notch spinner fw-semibold"></i>
                Xem thêm
            </button>

            <button wire:click="loadMore" wire:loading.remove class="shadow btn btn-primary fw-semibold flex-fill">
                Xem thêm
            </button>
        </div>
    @endif
</div>
