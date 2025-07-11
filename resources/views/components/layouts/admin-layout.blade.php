<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('includes.head')

<body>

    <!-- Main navbar -->
    @include('includes.header')
    <!-- /main navbar -->
    <div id="overlay" class="hidden"></div>

    <!-- Page content -->
    <div class="page-content">

        <!-- Main sidebar -->
        @include('includes.sidebar')
        <!-- /main sidebar -->


        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Inner content -->
            <div class="content-inner">

                <!-- Page header -->
                @if (isset($header))
                    {{ $header }}
                @endif
                <!-- /page header -->


                <!-- Content area -->
                {{ $slot }}
                <!-- /content area -->


                <!-- Footer -->
                @include('includes.footer')
                <!-- /footer -->

            </div>
            <!-- /inner content -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

    @php
        $user = auth()->user();
        $userData = $user->user_data;

        if (!$userData) {
            $userData = app(App\Services\SsoService::class)->getDataUser();
        }

        $facultyId = $userData['role'] === \App\Enums\Role::SuperAdmin->value ? $user->faculty_id : $userData['faculty_id'] ?? null;
    @endphp

    <style>
        #overlay {
            position: fixed;
            top: 78px;
            left: 0;
            width: 100%;
            height: calc(100% - 78px);
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none;
        }

        .hidden {
            display: none;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let facultyId = @json($facultyId ?? null);
            if (!facultyId) {
                document.getElementById('overlay').style.display = "block";
            }
        });
    </script>

    {{ $scripts ?? '' }}

    @livewireScripts

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session()->has('success'))
                new Noty({
                    type: 'success',
                    layout: 'topRight',
                    theme: 'metroui',
                    text: @json(session()->pull('success')),
                    timeout: 3000
                }).show();
            @endif

            @if (session()->has('error'))
                new Noty({
                    type: 'error',
                    layout: 'topRight',
                    theme: 'metroui',
                    text: @json(session()->pull('error')),
                    timeout: 3000
                }).show();
            @endif
    });
    </script>

</body>

</html>