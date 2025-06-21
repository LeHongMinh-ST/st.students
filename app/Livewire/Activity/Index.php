<?php

declare(strict_types=1);

namespace App\Livewire\Activity;

use App\Helpers\Constants;
use App\Models\LogActivity;
use App\Services\SsoService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'from')]
    public ?string $fromDate = null;

    #[Url(as: 'to')]
    public ?string $toDate = null;

    // Biến để lưu trữ ID của hoạt động đang xem chi tiết
    public ?int $viewingActivityId = null;

    // Biến để lưu trữ chi tiết của hoạt động đang xem
    public ?LogActivity $viewingActivity = null;

    // Biến để kiểm soát hiển thị modal
    public bool $showDetailModal = false;

    public function mount(): void
    {
        // Mặc định hiển thị hoạt động trong 7 ngày gần nhất
        if (!$this->fromDate) {
            $this->fromDate = now()->subDays(7)->format('Y-m-d');
        }

        if (!$this->toDate) {
            $this->toDate = now()->format('Y-m-d');
        }
    }

    public function render()
    {
        $facultyId = app(SsoService::class)->getFacultyId();

        $activities = LogActivity::query()
            ->where('faculty_id', $facultyId)
            ->when($this->search, function ($query): void {
                $query->where(function ($q): void {
                    $searchTerm = '%' . $this->search . '%';
                    $q->where('user_name', 'like', $searchTerm)
                        ->orWhere('action', 'like', $searchTerm)
                        ->orWhere('details', 'like', $searchTerm);
                });
            })
            ->when($this->fromDate, function ($query): void {
                $query->whereDate('created_at', '>=', $this->fromDate);
            })
            ->when($this->toDate, function ($query): void {
                $query->whereDate('created_at', '<=', $this->toDate);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE);

        return view('livewire.activity.index', [
            'activities' => $activities
        ]);
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'fromDate', 'toDate']);
        $this->fromDate = now()->subDays(7)->format('Y-m-d');
        $this->toDate = now()->format('Y-m-d');
    }

    /**
     * Mở modal xem chi tiết hoạt động
     *
     * @param int $activityId ID của hoạt động cần xem chi tiết
     * @return void
     */
    public function viewDetail(int $activityId): void
    {
        $this->viewingActivityId = $activityId;
        $this->viewingActivity = LogActivity::find($activityId);
        $this->showDetailModal = true;
        $this->dispatch('showModal');
    }

    /**
     * Đóng modal xem chi tiết
     *
     * @return void
     */
    public function closeDetail(): void
    {
        $this->showDetailModal = false;
        $this->viewingActivityId = null;
        $this->viewingActivity = null;
        $this->dispatch('hideModal');
    }

    public function placeholder()
    {
        return view('components.placeholders.table-placeholder');
    }
}
