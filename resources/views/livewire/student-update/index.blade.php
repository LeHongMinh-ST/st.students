<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Quản lý yêu cầu chỉnh sửa thông tin sinh viên</h5>
        </div>
        
        <div class="card-body">
            <!-- Tabs -->
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a href="#" class="nav-link {{ $activeTab === 'all' ? 'active' : '' }}" 
                       wire:click.prevent="setActiveTab('all')">
                        <i class="ph-list me-2"></i>
                        Tất cả yêu cầu
                    </a>
                </li>
                
                @if($isClassMonitor)
                <li class="nav-item">
                    <a href="#" class="nav-link {{ $activeTab === 'class-monitor' ? 'active' : '' }}" 
                       wire:click.prevent="setActiveTab('class-monitor')">
                        <i class="ph-clipboard-text me-2"></i>
                        Yêu cầu cần lớp trưởng duyệt
                    </a>
                </li>
                @endif
                
                @if($isTeacher)
                <li class="nav-item">
                    <a href="#" class="nav-link {{ $activeTab === 'teacher' ? 'active' : '' }}" 
                       wire:click.prevent="setActiveTab('teacher')">
                        <i class="ph-clipboard-text me-2"></i>
                        Yêu cầu cần giáo viên duyệt
                    </a>
                </li>
                @endif
                
                @if($isAdmin)
                <li class="nav-item">
                    <a href="#" class="nav-link {{ $activeTab === 'admin' ? 'active' : '' }}" 
                       wire:click.prevent="setActiveTab('admin')">
                        <i class="ph-clipboard-text me-2"></i>
                        Yêu cầu cần quản trị duyệt
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->isStudent())
                <li class="nav-item">
                    <a href="#" class="nav-link {{ $activeTab === 'my-requests' ? 'active' : '' }}" 
                       wire:click.prevent="setActiveTab('my-requests')">
                        <i class="ph-user-circle me-2"></i>
                        Yêu cầu của tôi
                    </a>
                </li>
                @endif
            </ul>
            
            <!-- Tab content -->
            <div class="tab-content">
                <!-- Tab: Tất cả yêu cầu -->
                <div class="tab-pane fade {{ $activeTab === 'all' ? 'show active' : '' }}">
                    <livewire:student.admin-approvals />
                </div>
                
                <!-- Tab: Yêu cầu cần lớp trưởng duyệt -->
                @if($isClassMonitor)
                <div class="tab-pane fade {{ $activeTab === 'class-monitor' ? 'show active' : '' }}">
                    <livewire:student.class-monitor-approvals />
                </div>
                @endif
                
                <!-- Tab: Yêu cầu cần giáo viên duyệt -->
                @if($isTeacher)
                <div class="tab-pane fade {{ $activeTab === 'teacher' ? 'show active' : '' }}">
                    <livewire:student.teacher-approvals />
                </div>
                @endif
                
                <!-- Tab: Yêu cầu cần quản trị duyệt -->
                @if($isAdmin)
                <div class="tab-pane fade {{ $activeTab === 'admin' ? 'show active' : '' }}">
                    <livewire:student.admin-approvals />
                </div>
                @endif
                
                <!-- Tab: Yêu cầu của tôi -->
                @if(Auth::user()->isStudent())
                <div class="tab-pane fade {{ $activeTab === 'my-requests' ? 'show active' : '' }}">
                    <livewire:student-update.my-requests />
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
