<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use App\Enums\SocialPolicyObject;
use App\Enums\StudentRole;
use App\Enums\StudentStatus;
use App\Enums\TrainingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
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
        'user_id',
    ];

    protected $casts = [
        'status' => StudentStatus::class,
        'role' => StudentRole::class,
        'social_policy_object' => SocialPolicyObject::class,
        'gender' => Gender::class,
        'training_type' => TrainingType::class,
    ];

    public function families(): HasMany
    {
        return $this->hasMany(Family::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(ClassGenerate::class, 'class_students')
            ->withPivot(['role', 'start_date', 'end_date', 'status'])
            ->withTimestamps();
    }

    public function warnings(): BelongsToMany
    {
        return $this->belongsToMany(Warning::class, 'student_warnings')
            ->withPivot(['note'])
            ->withTimestamps();
    }

    public function quits(): BelongsToMany
    {
        return $this->belongsToMany(Quit::class, 'student_quits')
            ->withPivot(['note_quit'])
            ->withTimestamps();
    }

    public function graduationCeremonies(): BelongsToMany
    {
        return $this->belongsToMany(GraduationCeremony::class, 'graduation_ceremony_students')
            ->withTimestamps();
    }
}
