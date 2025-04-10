<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Enums\FamilyRelationship;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FamilyRelationshipBadge extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?FamilyRelationship $relationship
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.family-relationship-badge');
    }
}
