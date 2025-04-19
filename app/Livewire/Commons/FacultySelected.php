<?php

declare(strict_types=1);

namespace App\Livewire\Commons;

use App\Services\SsoService;

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
        // Chỉ lấy faculty_id từ database
        if (auth()->check() && auth()->user()->faculty_id) {
            $this->facultyId = auth()->user()->faculty_id;
        }
    }

    public function updatedFacultyId($facultyId): void
    {
        // Chỉ lưu vào database
        if (auth()->check()) {
            auth()->user()->update(['faculty_id' => $facultyId]);
        }

        $this->dispatch('reloadPage');
    }

    private function fetchData()
    {
        $responses = cache()->remember('faculties', 60, fn () => app(SsoService::class)->get('/api/faculties/get-all'));

        return $responses['data'] ?? [];
    }
}
