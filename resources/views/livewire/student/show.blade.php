<div class="d-lg-flex align-items-lg-start">

    <!-- Left sidebar component -->
    <div class="bg-transparent shadow-none sidebar sidebar-component sidebar-expand-lg me-lg-3">

        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- Navigation -->
            <div class="card">
                <ul class="nav nav-sidebar" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#" class="nav-link @if ($tab == 'profile') active @endif" wire:click="setTab('profile')">
                            <i class="mr-2 ph-user"></i>
                            Thông tin sinh viên
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a href="#" class="nav-link @if ($tab == 'family') active @endif" wire:click="setTab('family')">
                            <i class="mr-2 ph-users-three"></i>
                            Thông tin gia đình
                        </a>
                    </li>

                    <li class="nav-item-divider"></li>
                    <li class="nav-item" role="presentation">
                        <a href="" class="nav-link">
                            <i class="mr-2 ph-note-pencil"></i><span>Chỉnh sửa</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- /navigation -->

        </div>
        <!-- /sidebar content -->

    </div>
    <!-- /left sidebar component -->

    <!-- Right content -->
    <div class="tab-content flex-fill">
        <div class="tab-pane fade @if ($tab == 'profile') show active @endif" id="profile" role="tabpanel">
            <div class="card">

                <div class="card-header">
                    <h5 class="mb-0"><i class="ph-user me-2"></i>Thông tin sinh viên</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="pt-0 text-center">
                                <div class="mb-3 card-img-actions d-inline-block">
                                    <img class="img-fluid rounded-circle" src="{{ Avatar::create($student->fullName)->toBase64() }}" width="150" height="150" alt="">
                                </div>

                                <h6 class="mb-0">{{ $student->fullName }}</h6>
                                <span class="text-muted">Khoá {{ $student->admissionYear->admission_year }}</span>
                                @if ($editStatusMode)
                                    <div class="gap-2 d-flex align-items-center justify-content-center">
                                        <select class="form-select" wire:model.live="studentStatus">
                                            @foreach (\App\Enums\StudentStatus::cases() as $item)
                                                <option value="{{ $item->value }}" @if ($item->value == $student->status) selected @endif>{{ $item->getLabel() }}</option>
                                            @endforeach
                                        </select>
                                        <span class="cursor-pointer" wire:click="$set('editStatusMode', false)">
                                            <i class="mr-2 ph-x"></i>
                                        </span>
                                    </div>
                                @else
                                    <div class="gap-2 d-flex align-items-center justify-content-center">
                                        <x-student-status-badge :status="$student->status" />
                                        <span class="cursor-pointer" wire:click="$set('editStatusMode', true)">
                                            <i class="mr-2 ph-note-pencil"></i>
                                        </span>
                                    </div>
                                @endif

                            </div>

                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-6 col-12">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade @if ($tab == 'family') active show @endif" id="permission" role="tabpanel">

            <div class="card">

            </div>
        </div>

    </div>
    <!-- /right content -->

</div>
