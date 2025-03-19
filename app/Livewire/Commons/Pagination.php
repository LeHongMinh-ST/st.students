<?php

namespace App\Livewire\Commons;

use Livewire\Component;

class Pagination extends Component
{
    public $currentPage;

    public $totalPages;

    public function mount($currentPage = 1, $totalPages = 1)
    {
        $this->currentPage = $currentPage;
        $this->totalPages = $totalPages;
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->dispatch('onPageChange', $this->currentPage);
        }
    }

    public function nextPage()
    {
        if ($this->currentPage < $this->totalPages) {
            $this->currentPage++;
            $this->dispatch('onPageChange', $this->currentPage);
        }
    }

    public function gotoPage($page)
    {
        if ($page >= 1 && $page <= $this->totalPages) {
            $this->currentPage = $page;
            $this->dispatch('onPageChange', $this->currentPage);
        }
    }

    public function render()
    {
        return view('livewire.commons.pagination');
    }
}
