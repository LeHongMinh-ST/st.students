<?php

declare(strict_types=1);

namespace App\Http\Resources\AdmissionYear;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdmissionYearResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? 0,
            'admission_year' => $this->admission_year ?? '',
            'school_year' => $this->school_year ?? 0,
            'student_count' => $this?->students_count ?? 0,
            'classes_count' => $this?->general_classes_count ?? 0,
            'currently_studying_count' => $this?->currently_studying_count ?? 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
