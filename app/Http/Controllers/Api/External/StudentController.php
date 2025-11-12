<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\External;

use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Http\Resources\Student\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {

        $auth = auth('api')->user();
        if (in_array($auth->role, ['officer', 'system admin'])) {
            return response()->json([
                'message' => 'You are not authorized to perform this action',
                'code' => 403
            ]);
        }
        $facultyId = $auth->faculty_id;
        $students = Student::where('faculty_id', $facultyId)
            ->when($request->q, function ($query) use ($request): void {
                $query->search($request->q);
            })
            ->when($request->graduation_ceremony_id, function ($query) use ($request): void {
                $query->whereHas('graduationCeremonies', function ($q) use ($request): void {
                    $q->where('graduation_ceremonies.id', $request->graduation_ceremony_id);
                });
            })
            ->when(is_array($request->graduation_ceremony_ids), function ($query) use ($request): void {
                $query->whereHas('graduationCeremonies', function ($q) use ($request): void {
                    $q->whereIn('graduation_ceremonies.id', $request->graduation_ceremony_ids);
                });
            })
            ->when(is_array($request->ids), function ($query) use ($request): void {
                $query->whereIn('id', $request->ids);
            })
            ->when($request->class_id, function ($query) use ($request): void {
                $query->whereHas('classes', function ($q) use ($request): void {
                    $q->where('class_id', $request->class_id);
                });
            })
            ->when($request->is_graduate, function ($query): void {
                $query->whereHas('graduationCeremonies');
            })
            ->when($request->with_family, function ($query): void {
                $query->with(['families']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE);


        return StudentResource::collection($students);
    }
}
