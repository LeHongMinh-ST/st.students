<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\External;

use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrainingIndustry\TrainingIndustryResource;
use App\Models\TrainingIndustry;
use Illuminate\Http\Request;

class TrainingIndustryController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $auth = auth('api')->user();
        $facultyId = $auth->faculty_id;
        $query = TrainingIndustry::when($facultyId, function ($query) use ($facultyId): void {
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

        $trainingIndustries = $query->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE);

        return TrainingIndustryResource::collection($trainingIndustries);
    }
}
