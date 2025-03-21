<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use App\Enums\SocialPolicyObject;
use App\Enums\StudentUpdateStatus;
use App\Enums\TrainingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentUpdate extends Model
{
    protected $fillable = [
        'role',
        'status',
        'school_year',
        'person_email',
        'gender',
        'permanet_residence',
        'dob',
        'pob',
        'address',
        'countryside',
        'training_type',
        'phone',
        'nationality',
        'citizen_identification',
        'ethnic',
        'religion',
        'thumbnail',
        'social_policy_object',
        'note',
        'change_column',
        'student_id',
    ];

    protected $casts = [
        'status' => StudentUpdateStatus::class,
        'gender' => Gender::class,
        'training_type' => TrainingType::class,
        'social_policy_object' => SocialPolicyObject::class,
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
