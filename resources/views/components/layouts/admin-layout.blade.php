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


    @livewireScripts

    @php
        $facultyId = session('facultyId');
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
        document.addEventListener("DOMContentLoaded", function() {
            let facultyId = @json($facultyId ?? null);
            if (!facultyId) {
                document.getElementById('overlay').style.display = "block";
            }
        });
    </script>
</body>

</html>
