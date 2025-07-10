<?php

declare(strict_types=1);

namespace App\Livewire\Commons;

use App\Enums\Role;
use App\Services\SsoService;
use Exception;
use Illuminate\Support\Facades\Log;
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
        if (auth()->check() && auth()->user()->faculty_id && Role::SuperAdmin !== auth()->user()->role) {
            $this->facultyId = auth()->user()->faculty_id;
        }
    }

    public function updatedFacultyId($facultyId)
    {
        try {
            Log::info('Update faculty');
            // Chỉ lưu vào database
            if (auth()->check()) {
                auth()->user()->update(['faculty_id' => $facultyId]);
            }

        } catch (Exception $e) {
            Log::error('');
            Log::error($e->getMessage());
        }

        Log::info('Redirect');
        return redirect('/dashboard');
    }

    private function fetchData()
    {
        $responses = cache()->remember('faculties', 60, fn () => app(SsoService::class)->get('/api/faculties/get-all'));
        return $responses['data'] ?? [];
    }
}
