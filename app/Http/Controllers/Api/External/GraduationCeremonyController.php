<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\External;

use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Http\Resources\GraduationCeremony\GraduationCeremonyResource;
use App\Models\GraduationCeremony;
use Illuminate\Http\Request;

class GraduationCeremonyController extends Controller
{
    /**
     * Get training industries by faculty ID.
     *
     * @param int $facultyId
     */
    public function getByFaculty(Request $request, $facultyId)
    {
        $query = GraduationCeremony::with('students')->when($facultyId, function ($query) use ($facultyId): void {
            $query->where('faculty_id', $facultyId);
        });

        // Search by name or code if provided
        if ($request->has('q') && $request->q) {
            $search = $request->q;
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $trainingIndustries = $query->withCount('students')->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE);

        return GraduationCeremonyResource::collection($trainingIndustries);
    }
}
