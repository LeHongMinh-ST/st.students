<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- Sidebar header -->
        <div class="sidebar-section">
            <div class="sidebar-section-body d-flex justify-content-center">
                <h5 class="my-auto sidebar-resize-hide flex-grow-1">ST Students</h5>

                <div>
                    <button type="button"
                            class="border-transparent btn btn-flat-white btn-icon btn-sm rounded-pill sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                        <i class="ph-arrows-left-right"></i>
                    </button>

                    <button type="button"
                            class="border-transparent btn btn-flat-white btn-icon btn-sm rounded-pill sidebar-mobile-main-toggle d-lg-none">
                        <i class="ph-x"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- /sidebar header -->


        <!-- Main navigation -->
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <li class="nav-item-header">
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="ph-house"></i>
                        <span>Bảng điều khiển</span>
                    </a>
                </li>

                <li class="nav-item-header">
                    <div class="opacity-50 text-uppercase fs-sm lh-sm sidebar-resize-hide">Lớp học</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>

                <li class="nav-item">
                    <a href=""
                       class="nav-link {{ request()->routeIs('') ? 'active' : '' }}">
                        <i class="ph-books"></i>
                        <span>Danh sách lớp học</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href=""
                       class="nav-link {{ request()->routeIs('') ? 'active' : '' }}">
                        <i class="ph-address-book"></i>
                        <span>Lớp học chủ nhiệm</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href=""
                       class="nav-link {{ request()->routeIs('') ? 'active' : '' }}">
                        <i class="ph-book-bookmark"></i>
                        <span>Cố vấn học tập</span>
                    </a>
                </li>

                <li class="nav-item-header">
                    <div class="opacity-50 text-uppercase fs-sm lh-sm sidebar-resize-hide">Sinh viên</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>

                <li class="nav-item">
                    <a href="{{ route('students.index') }}"
                       class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                        <i class="ph-users-four"></i>
                        <span>Khóa sinh viên</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href=""
                       class="nav-link {{ request()->routeIs('') ? 'active' : '' }}">
                        <i class="ph-student"></i>
                        <span>Sinh viên tốt nghiệp</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a href=""
                       class="nav-link {{ request()->routeIs('') ? 'active' : '' }}">
                        <i class="ph-warning-circle"></i>
                        <span>Cảnh báo sinh viên</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href=""
                       class="nav-link {{ request()->routeIs('') ? 'active' : '' }}">
                        <i class="ph-x-circle"></i>
                        <span>Buộc thôi học</span>
                    </a>
                </li>

                <li class="nav-item-header">
                    <div class="opacity-50 text-uppercase fs-sm lh-sm sidebar-resize-hide">Thông báo</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>

                <li class="nav-item">
                    <a href=""
                       class="nav-link {{ request()->routeIs('posts') ? 'active' : '' }}">
                        <i class="ph-note"></i>
                        <span>Bài viết</span>
                    </a>
                </li>

                @if (Auth::user()->can('viewAny', \App\Models\User::class) || Auth::user()->can('viewAny', \App\Models\Role::class))
                    <li class="nav-item-header">
                        <div class="opacity-50 text-uppercase fs-sm lh-sm sidebar-resize-hide">Hệ thống</div>
                        <i class="ph-dots-three sidebar-resize-show"></i>
                    </li>
                @endif


                @can('viewAny', \App\Models\User::class)
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}"
                           class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="ph-user"></i>
                            <span>Người dùng</span>
                        </a>
                    </li>
                @endcan
                @can('viewAny', \App\Models\Role::class)
                    <li class="nav-item">
                        <a href="{{ route('roles.index') }}"
                           class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            <i class="ph-lock"></i>
                            <span>Vai trò</span>
                        </a>
                    </li>
                @endcan


            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>
