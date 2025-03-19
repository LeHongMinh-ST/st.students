<?php

declare(strict_types=1);

namespace App\Livewire\Commons;

use App\Services\SsoService;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class FacultySelected extends Component
{
    public $facultyId;

    public function render()
    {
        $faculties = $this->fetchData();

        return view('livewire.commons.faculty-selected', [
            'faculties' => $faculties,
        ]);
    }

    public function mount(): void
    {
        $sessionFaculty = Session::get('facultyId');
        if ($sessionFaculty) {
            $this->facultyId = $sessionFaculty;
        }
    }

    public function updatedFacultyId($facultyId): void
    {
        Session::put('facultyId', $facultyId);

        $this->dispatch('reloadPage');
    }

    private function fetchData()
    {
        $responses = cache()->remember('faculties', 60, fn () => app(SsoService::class)->get('/api/faculties/get-all'));

        return $responses['data'] ?? [];
    }
}
