<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\External;

use App\Enums\Status;
use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdmissionYear\AdmissionYearResource;
use App\Models\AdmissionYear;
use Illuminate\Http\Request;

class AdmissionYearController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Get training industries by faculty ID.
     *
     * @param int $facultyId
     */
    public function getByFaculty(Request $request, $facultyId)
    {
        $query = AdmissionYear::when($facultyId, function ($query) use ($facultyId): void {
            $query->where('faculty_id', $facultyId);
        });

        // Search by name or code if provided
        if ($request->has('q') && $request->q) {
            $search = $request->q;
            $query->where(function ($q) use ($search): void {
                $q->where('admission_year', 'like', "%{$search}%")
                    ->orWhere('school_year', 'like', "%{$search}%");
            });
        }

        $query = $query->withCount([
            'students' => function ($query) use ($facultyId): void {
                $query->where('faculty_id', $facultyId);
            },
            'generalClasses' => function ($query) use ($facultyId): void {
                $query
                    ->where('status', Status::Active)
                    ->where('faculty_id', $facultyId);
            }
        ]);

        $admissionYears = $query->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE);

        return AdmissionYearResource::collection($admissionYears);
    }
}
