<?php

declare(strict_types=1);

namespace App\View\Components\Student;

use App\Enums\StudentStatus;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    public StudentStatus $status;
    /**
     * Create a new component instance.
     */
    public function __construct(StudentStatus $status)
    {
        $this->status = $status;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.student.status-badge');
    }

    public function getBadgeClasses(): string
    {
        return match ($this->status) {
            StudentStatus::CurrentlyStudying => 'badge bg-light border-start border-width-3 text-body rounded-start-0 border-primary',
            StudentStatus::Graduated => 'badge bg-light border-start border-width-3 text-body rounded-start-0 border-success',
            StudentStatus::ToDropOut => 'badge bg-light border-start border-width-3 text-body rounded-start-0 border-warning',
            StudentStatus::TemporarilySuspended => 'badge bg-light border-start border-width-3 text-body rounded-start-0 border-dark',
            StudentStatus::Expelled => 'badge bg-light border-start border-width-3 text-body rounded-start-0 border-danger',
            StudentStatus::Deferred => 'badge bg-light border-start border-width-3 text-body rounded-start-0 border-info',
            StudentStatus::TransferStudy => 'badge bg-light border-start border-width-3 text-body rounded-start-0 border-secondary',
        };
    }
}
