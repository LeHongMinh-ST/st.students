<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Enums\Status;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ClassStatusBadge extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Status $status
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.class-status-badge');
    }
}
