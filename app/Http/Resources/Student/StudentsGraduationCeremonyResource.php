<?php

declare(strict_types=1);

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentsGraduationCeremonyResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->last_name . ' ' . $this->first_name,
            'email' => $this->email,
            'email_edu' => $this->email_edu,
            'class' => $this->classes->first()?->name,
            'code' => $this->code,
            'status' => $this->status,
            'school_year_start' => $this->school_year_start,
            'school_year_end' => $this->school_year_end,
            'gender' => $this->gender,
            // 'training_industry_id' => $this->training_industry_id,
            'citizen_identification' => $this->citizen_identification,
            'ethnic' => $this->ethnic,
            'religion' => $this->religion,
            'thumbnail' => $this->thumbnail,
            'social_policy_object' => $this->social_policy_object,
            'note' => $this->note,
            'user_id' => $this->user_id,
            'admission_year_id' => $this->admission_year_id,
            'faculty_id' => $this->faculty_id,
            'permanent_residence' => $this->permanent_residence,
            'dob' => $this->dob,
            'pob' => $this->pob,
            'address' => $this->address,
            'countryside' => $this->countryside,
            'training_type' => $this->training_type,
            'phone' => $this->phone,
            'nationality' => $this->nationality,
        ];
    }
}
