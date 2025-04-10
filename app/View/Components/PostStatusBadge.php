<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Enums\PostStatus;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PostStatusBadge extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public PostStatus $status
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.post-status-badge');
    }
}
