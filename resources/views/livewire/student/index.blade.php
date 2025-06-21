<div class="py-1 mb-3">
    <h5 class="mb-0">
        Khóa {{ $admissionYear->admission_year }}
    </h5>
    <span class="text-muted">Năm học {{ $admissionYear->school_year }}</span>
</div>

<livewire:student.student-list :admissionYear="$admissionYear" lazy />