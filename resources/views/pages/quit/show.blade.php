<x-admin-layout>
    <x-slot name="header">
        <div class="page-header">
            <div class="page-header-content d-lg-flex">
                <div class="d-flex">
                    <h4 class="page-title mb-0">
                        Quản lý thôi học <span class="fw-normal">Chi tiết đợt buộc thôi học</span>
                    </h4>

                    <a href="#breadcrumb_elements" class="p-0 border-transparent btn btn-light align-self-center collapsed d-lg-none rounded-pill ms-auto" data-bs-toggle="collapse">
                        <i class="m-1 ph-caret-down collapsible-indicator ph-sm"></i>
                    </a>
                </div>

            </div>

            <div class="page-header-content d-lg-flex border-top">
                <div class="d-flex">
                    <div class="py-2 breadcrumb">
                        <a href="{{ route('dashboard') }}" class="breadcrumb-item"><i class="ph-house"></i></a>
                        <a href="{{ route('quits.index') }}" class="breadcrumb-item">Đợt buộc thôi học</a>
                        <span class="breadcrumb-item active">{{ $quit->name }}</span>
                    </div>

                    <a href="#breadcrumb_elements" class="p-0 border-transparent btn btn-light align-self-center collapsed d-lg-none rounded-pill ms-auto" data-bs-toggle="collapse">
                        <i class="m-1 ph-caret-down collapsible-indicator ph-sm"></i>
                    </a>
                </div>

            </div>
        </div>
    </x-slot>


    <div class="content">
        <livewire:quit.show :quit="$quit" />
    </div>
</x-admin-layout>
