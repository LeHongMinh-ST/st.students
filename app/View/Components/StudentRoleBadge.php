<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Enums\StudentRole;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StudentRoleBadge extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public StudentRole $role
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.student-role-badge');
    }
}
