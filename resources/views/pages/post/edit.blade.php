<x-admin-layout>
    <x-slot name="styles">
        <style>
            .ck-editor__editable_inline {
                min-height: 500px;
                max-height: 500px;
                overflow-y: auto;
            }
        </style>
    </x-slot>

    <x-slot name="scripts">
        <script src="{{ asset('assets/js/vendor/editors/ckeditor/ckeditor_classic.js') }}"></script>
    </x-slot>
    <x-slot name="header">
        <div class="page-header">
            <div class="page-header-content d-lg-flex">
                <div class="d-flex">
                    <h4 class="page-title mb-0">
                        Quản lý bài viết <span class="fw-normal">Chỉnh sửa bài viết</span>
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
                        <a href="{{ route('posts.index') }}" class="breadcrumb-item">Bài viết</a>
                        <span class="breadcrumb-item active">{{ $post->title }}</span>
                    </div>

                    <a href="#breadcrumb_elements" class="p-0 border-transparent btn btn-light align-self-center collapsed d-lg-none rounded-pill ms-auto" data-bs-toggle="collapse">
                        <i class="m-1 ph-caret-down collapsible-indicator ph-sm"></i>
                    </a>
                </div>

            </div>
        </div>
    </x-slot>


    <div class="content">
        <livewire:post.edit :post="$post" />
    </div>
</x-admin-layout>
