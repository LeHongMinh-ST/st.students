<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\External;

use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrainingIndustry\TrainingIndustryResource;
use App\Models\ClassGenerate;
use Illuminate\Http\Request;

class ClassGenerateController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        $auth = auth('api')->user();
        $facultyId = $auth->faculty_id;

        $authData = $auth->user_data;
        if (in_array($authData['role'], ['officer', 'system admin'])) {
            return response()->json([
                'message' => 'You are not authorized to perform this action',
                'code' => 403
            ]);
        }

        $query = ClassGenerate::when($facultyId, function ($query) use ($facultyId): void {
            $query->where('faculty_id', $facultyId);
        });

        // where admission_year_id if has admission_year_id
        if ($request->has('admission_year_id') && $request->admission_year_id) {
            $query->where('admission_year_id', $request->admission_year_id);
        }

        // where training_industry_id if has training_industry_id
        if ($request->has('training_industry_id') && $request->training_industry_id) {
            $query->where('training_industry_id', $request->training_industry_id);
        }

        // Search by name or code if provided
        if ($request->has('q') && $request->q) {
            $search = $request->q;
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $trainingIndustries = $query->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE);

        return TrainingIndustryResource::collection($trainingIndustries);
    }
}
