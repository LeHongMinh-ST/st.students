<?php

declare(strict_types=1);

namespace App\Http\Resources\GeneralClass;

use App\Http\Resources\AdmissionYear\AdmissionYearResource;
use App\Http\Resources\TrainingIndustry\TrainingIndustryResource;
use App\Http\Resources\User\UserForLoadResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeneralClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status,
            'teacher_id' => $this->teacher_id,
            'sub_teacher_id' => $this->sub_teacher_id,
            'faculty_id' => $this->faculty_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'teacher' => new UserForLoadResource($this->whenLoaded('teacher')),
            'admission_year_id' => $this->admission_year_id,
            'admission_year' => new AdmissionYearResource($this->whenLoaded('admissionYear')),
            'students_count' => $this->whenLoaded('students', fn () => $this->students->count()),
            'training_industry' => new TrainingIndustryResource($this->whenLoaded('trainingIndustry')),
            'training_industry_id' => $this->training_industry_id
        ];
    }
}
