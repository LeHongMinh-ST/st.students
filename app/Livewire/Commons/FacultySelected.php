<?php

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

    public function mount()
    {
        $sessionFaculty = Session::get('facultyId');
        if ($sessionFaculty) {
            $this->facultyId = $sessionFaculty;
        }
    }

    private function fetchData()
    {
        $responses = app(SsoService::class)->get('/api/faculties/get-all');

        return $responses['data'] ?? [];
    }

    public function updatedFacultyId($facultyId)
    {
        Session::put('facultyId', $facultyId);

        $this->dispatch('reloadPage');
    }
}
