<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use App\Enums\SocialPolicyObject;
use App\Enums\TrainingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentUpdatesHistory extends Model
{
    protected $table = 'student_updates_history';

    protected $fillable = [
        'person_email',
        'gender',
        'permanent_residence',
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
        'student_info_update_id',
    ];

    protected $casts = [
        'gender' => Gender::class,
        'training_type' => TrainingType::class,
        'social_policy_object' => SocialPolicyObject::class,
        'dob' => 'date',
    ];

    public function studentUpdate(): BelongsTo
    {
        return $this->belongsTo(StudentUpdate::class, 'student_info_update_id');
    }
}
