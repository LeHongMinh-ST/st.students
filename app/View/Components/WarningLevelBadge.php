<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Enums\WarningLevel;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WarningLevelBadge extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public WarningLevel $level
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.warning-level-badge');
    }
}
