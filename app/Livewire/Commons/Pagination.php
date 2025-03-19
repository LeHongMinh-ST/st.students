<?php

declare(strict_types=1);

namespace App\Livewire\Commons;

use Livewire\Component;

class Pagination extends Component
{
    public $currentPage;

    public $totalPages;

    public function mount($currentPage = 1, $totalPages = 1): void
    {
        $this->currentPage = $currentPage;
        $this->totalPages = $totalPages;
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->dispatch('onPageChange', page: $this->currentPage);
        }
    }

    public function nextPage(): void
    {
        if ($this->currentPage < $this->totalPages) {
            $this->currentPage++;
            $this->dispatch('onPageChange', page: $this->currentPage);
        }
    }

    public function gotoPage($page): void
    {
        if ($page >= 1 && $page <= $this->totalPages) {
            $this->currentPage = $page;
            $this->dispatch('onPageChange', page: $this->currentPage);
        }
    }

    public function render()
    {
        return view('livewire.commons.pagination');
    }
}
