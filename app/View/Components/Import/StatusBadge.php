<?php

declare(strict_types=1);

namespace App\View\Components\Import;

use App\Enums\StatusImport;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    public StatusImport $status;

    public function __construct(StatusImport $status)
    {
        $this->status = $status;
    }

    public function render()
    {
        return view('components.import.status-badge');
    }

    public function getBadgeClasses(): string
    {
        return match ($this->status) {
            StatusImport::Pending => 'badge bg-light border-start border-width-3 text-body rounded-start-0 border-warning',
            StatusImport::Processing => 'badge bg-light border-start border-width-3 text-body rounded-start-0 border-primary',
            StatusImport::Completed => 'badge bg-light border-start border-width-3 text-body rounded-start-0 border-success',
            StatusImport::Failed => 'badge bg-light border-start border-width-3 text-body rounded-start-0 border-danger',
            StatusImport::PartialyFaild => 'badge bg-light border-start border-width-3 text-body rounded-start-0 border-secondary',
        };
    }
}
