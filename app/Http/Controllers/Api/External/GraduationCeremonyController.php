<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\External;

use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Http\Resources\GraduationCeremony\GraduationCeremonyResource;
use App\Http\Resources\Student\StudentsGraduationCeremonyResource;
use App\Models\GraduationCeremony;
use Illuminate\Http\Request;

class GraduationCeremonyController extends Controller
{
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


    public function show(int $id)
    {
        $auth = auth('api')->user();

        $authData = $auth->user_data;
        if (in_array($authData['role'], ['officer', 'system admin'])) {
            return response()->json([
                'message' => 'You are not authorized to perform this action',
                'code' => 403
            ]);
        }
        $graduationCeremony = GraduationCeremony::where('faculty_id', $auth->faculty_id)->findOrFail($id);

        return GraduationCeremonyResource::make($graduationCeremony);
    }


    public function students(int $id)
    {
        $auth = auth('api')->user();
        $authData = $auth->user_data;
        if (in_array($authData['role'], ['officer', 'system admin'])) {
            return response()->json([
                'message' => 'You are not authorized to perform this action',
                'code' => 403
            ]);
        }
        $graduationCeremony = GraduationCeremony::where('faculty_id', $auth->faculty_id)->findOrFail($id);

        $students = $graduationCeremony->students()->paginate(Constants::PER_PAGE);

        return StudentsGraduationCeremonyResource::collection($students);
    }

    public function showTotal(Request $request)
    {
        $ids = $request->get('ids', []);
        $auth = auth('api')->user();
        $authData = $auth->user_data;
        if (!in_array($authData['role'], ['officer', 'system admin'])) {
            return response()->json([
                'message' => 'You are not authorized to perform this action',
                'code' => 403
            ]);
        }
        $total = GraduationCeremony::where('faculty_id', $auth->faculty_id)
            ->whereIn('id', $ids)
            ->withCount('students')
            ->get()
            ->sum('students_count');

        return response()->json([
            'survey_students' => $total
        ]);
    }

    public function showAll()
    {
        $auth = auth('api')->user();
        $authData = $auth->user_data;
        if (!in_array($authData['role'], ['officer', 'system admin'])) {
            return response()->json([
                'message' => 'You are not authorized to perform this action',
                'code' => 403
            ]);
        }
        $graduationCeremonies = GraduationCeremony::where('faculty_id', $auth->faculty_id)
            ->withCount('students')
            ->orderBy('created_at', 'desc')
            ->get();

        return GraduationCeremonyResource::collection($graduationCeremonies);
    }
}
