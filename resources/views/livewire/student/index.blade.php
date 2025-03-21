<div>
    @if ($admissionYear)
        <div class="py-2 mb-3">
            <h5 class="mb-0">
                Khóa {{ $admissionYear->admission_year }}
            </h5>
            <span class="text-muted">Năm học {{ $admissionYear->shool_year }}</span>

        </div>
    @else
        <div class="py-2 mb-3">
            <h5 class="mb-0">
                Khóa học
            </h5>
            <span class="text-muted">Danh sách khóa học</span>

        </div>
        <livewire:student.admission-list />
    @endif
</div>
