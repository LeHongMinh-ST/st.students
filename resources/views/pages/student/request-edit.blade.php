<x-admin-layout>
    <x-slot name="header">
        <div class="shadow page-header page-header-light">
            <div class="page-header-content d-lg-flex">
                <div class="d-flex">
                    <h4 class="mb-0 page-title">
                        Yêu cầu chỉnh sửa thông tin sinh viên - {{ $student->full_name }} - {{ $student->code }}
                    </h4>

                    <a href="#page_header" class="p-0 border-transparent btn btn-light align-self-center collapsed d-lg-none rounded-pill ms-auto" data-bs-toggle="collapse">
                        <i class="m-1 ph-caret-down collapsible-indicator ph-sm"></i>
                    </a>
                </div>

            </div>

            <div class="page-header-content d-lg-flex border-top">
                <div class="d-flex">
                    <div class="py-2 breadcrumb">
                        <a href="{{ route('dashboard') }}" class="breadcrumb-item"><i class="ph-house"></i></a>
                        <a href="{{ route('students.index', ['admission_year' => $student->admissionYear->admission_year]) }}" class="breadcrumb-item">Sinh viên</a>
                        <a href="{{ route('students.show', $student->id) }}" class="breadcrumb-item">{{ $student->full_name }} - {{ $student->code }}</a>
                        <span class="breadcrumb-item active">Yêu cầu chỉnh sửa thông tin</span>
                    </div>

                    <a href="#breadcrumb_elements" class="p-0 border-transparent btn btn-light align-self-center collapsed d-lg-none rounded-pill ms-auto" data-bs-toggle="collapse">
                        <i class="m-1 ph-caret-down collapsible-indicator ph-sm"></i>
                    </a>
                </div>

            </div>
        </div>
    </x-slot>


    <div class="content">
        <livewire:student.request-edit :student="$student" />
    </div>
</x-admin-layout>
