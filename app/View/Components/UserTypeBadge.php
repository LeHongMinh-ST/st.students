<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Enums\UserType;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserTypeBadge extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public UserType $type
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.user-type-badge');
    }
}
