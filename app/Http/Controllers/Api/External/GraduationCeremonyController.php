<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\External;

use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Http\Resources\GraduationCeremony\GraduationCeremonyResource;
use App\Http\Resources\Student\StudentsGraduationCeremonyResource;
use App\Models\GraduationCeremony;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GraduationCeremonyController extends Controller
{
    public function index(Request $request)
    {
        $auth = auth('api')->user();
        Log::info('DEBUG USER', [
            'id' => $auth->id,
            'role' => $auth->user_data['role'] ?? null,
            'faculty' => $auth->faculty_id,
            'source' => 'external',
        ]);
        // $authData = $auth->user_data;
        // if (!in_array($authData['role'], ['officer', 'system admin'])) {
        //     return response()->json([
        //         'message' => 'You are not authorized to perform this action',
        //         'code' => 403
        //     ]);
        // }
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

        // $authData = $auth->user_data;
        // if (!in_array($authData['role'], ['officer', 'system admin'])) {
        //     return response()->json([
        //         'message' => 'You are not authorized to perform this action',
        //         'code' => 403
        //     ]);
        // }
        $graduationCeremony = GraduationCeremony::where('faculty_id', $auth->faculty_id)->findOrFail($id);

        return GraduationCeremonyResource::make($graduationCeremony);
    }


    public function students(int $id)
    {
        $auth = auth('api')->user();
        // $authData = $auth->user_data;
        // if (!in_array($authData['role'], ['officer', 'system admin'])) {
        //     return response()->json([
        //         'message' => 'You are not authorized to perform this action',
        //         'code' => 403
        //     ]);
        // }
        $graduationCeremony = GraduationCeremony::where('faculty_id', $auth->faculty_id)->findOrFail($id);

        $students = $graduationCeremony->students()->paginate(Constants::PER_PAGE);

        return StudentsGraduationCeremonyResource::collection($students);
    }

    public function showTotal(Request $request)
    {
        $ids = $request->get('ids', []);
        $auth = auth('api')->user();
        // $authData = $auth->user_data;
        // if (!in_array($authData['role'], ['officer', 'system admin'])) {
        //     return response()->json([
        //         'message' => 'You are not authorized to perform this action',
        //         'code' => 403
        //     ]);
        // }
        $total = GraduationCeremony::where('faculty_id', $auth->faculty_id)
            ->whereIn('id', $ids)
            ->withCount('students')
            ->get()
            ->sum('students_count');

        return response()->json([
            'data' => [
                'survey_students' => $total
            ]
        ]);
    }

    public function allStudents(Request $request)
    {
        $ids = $request->get('ids', []);

        // Kiểm tra nếu ids không phải mảng hoặc rỗng thì trả về mảng rỗng
        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'data' => [
                    'survey_students' => []
                ]
            ]);
        }

        $auth = auth('api')->user();

        // Lấy các đợt tốt nghiệp thuộc khoa hiện tại và nằm trong danh sách ids gửi lên
        // Eager load 'students' để lấy luôn sinh viên
        $ceremonies = GraduationCeremony::where('faculty_id', $auth->faculty_id)
            ->whereIn('id', $ids)
            ->with('students')
            ->get();

        // Lấy ra collection students từ các ceremonies và gộp lại thành 1 mảng phẳng
        $students = $ceremonies->pluck('students')->flatten();

        return response()->json([
            'data' => [
                // Sử dụng Resource để format dữ liệu sinh viên giống hàm students() ở trên
                'survey_students' => StudentsGraduationCeremonyResource::collection($students)
            ]
        ]);
    }

    public function showAll()
    {
        $auth = auth('api')->user();
        // $authData = $auth->user_data;
        // if (!in_array($authData['role'], ['officer', 'system admin'])) {
        //     return response()->json([
        //         'message' => 'You are not authorized to perform this action',
        //         'code' => 403
        //     ]);
        // }
        $graduationCeremonies = GraduationCeremony::where('faculty_id', $auth->faculty_id)
            ->withCount('students')
            ->orderBy('created_at', 'desc')
            ->get();

        return GraduationCeremonyResource::collection($graduationCeremonies);
    }

    public function surveyGraduations(Request $request)
    {
        $auth = auth('api')->user();
        $ids = $request->get('ids', []);
        // $authData = $auth->user_data;
        // if (!in_array($authData['role'], ['officer', 'system admin'])) {
        //     return response()->json([
        //         'message' => 'You are not authorized to perform this action',
        //         'code' => 403
        //     ]);
        // }

        $graduations = GraduationCeremony::where('faculty_id', $auth->faculty_id)
            ->whereIn('id', $ids)
            ->select('id', 'name', 'school_year')
            ->get();

        return response()->json([
            'data' => [
                'graduations' => $graduations
            ]
        ]);
    }
}
