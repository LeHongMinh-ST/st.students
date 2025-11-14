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
    public function __construct() {}

    public function index(Request $request)
    {
        $auth = auth('api')->user();
        $authData = $auth->user_data;
        if (in_array($authData['role'], ['officer', 'system admin'])) {
            return response()->json([
                'message' => 'You are not authorized to perform this action',
                'code' => 403
            ]);
        }
        $facultyId = $auth->faculty_id;
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
