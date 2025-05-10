<?php

declare(strict_types=1);

namespace App\Livewire\StudentUpdate;

use App\Models\Student;
use App\Models\StudentUpdate;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyRequests extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    public function render()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return view('livewire.student-update.my-requests', [
                'requests' => StudentUpdate::where('id', 0)->paginate(10), // Empty paginator
            ]);
        }

        $requests = StudentUpdate::where('student_id', $student->id)
            ->when($this->status, function ($query): void {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.student-update.my-requests', [
            'requests' => $requests,
        ]);
    }
}
