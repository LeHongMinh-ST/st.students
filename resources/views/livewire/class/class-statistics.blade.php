<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="ph-chart-bar me-1"></i> Thống kê lớp học</h5>
    </div>
    <div class="card-body py-2">
        <div class="row g-2">
            <div class="col-md-2">
                <div class="card bg-primary text-white text-center p-2">
                    <h4 class="mb-0">{{ $totalStudents }}</h4>
                    <div class="small">Sĩ số ban đầu</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-success text-white text-center p-2">
                    <h4 class="mb-0">{{ $currentlyStudying }}</h4>
                    <div class="small">Đang học</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-info text-white text-center p-2">
                    <h4 class="mb-0">{{ $graduated }}</h4>
                    <div class="small">Đã tốt nghiệp</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-warning text-white text-center p-2">
                    <h4 class="mb-0">{{ $deferred }}</h4>
                    <div class="small">Bảo lưu</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-danger text-white text-center p-2">
                    <h4 class="mb-0">{{ $dropped }}</h4>
                    <div class="small">Đã nghỉ học</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-secondary text-white text-center p-2">
                    <h4 class="mb-0">{{ $warned }}</h4>
                    <div class="small">Cảnh báo</div>
                </div>
            </div>
        </div>
    </div>
</div>
