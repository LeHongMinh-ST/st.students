<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ClassType;
use App\Enums\Gender;
use App\Enums\SocialPolicyObject;
use App\Enums\Status;
use App\Enums\StudentRole;
use App\Enums\StudentStatus;
use App\Enums\TrainingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;

class Student extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_edu',
        'code',
        'status',
        'school_year_start',
        'school_year_end',
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
        'admission_year_id',
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

    public function currentClass(): HasOneThrough
    {
        return $this->hasOneThrough(ClassGenerate::class, ClassStudent::class, 'student_id', 'id', 'id', 'class_id')
            ->whereIn('classes.type', [ClassType::Basic, ClassType::Major])
            ->where('class_students.status', Status::Active->value)
            ->select('classes.*', 'class_students.role as role');
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

    public function admissionYear(): BelongsTo
    {
        return $this->belongsTo(AdmissionYear::class);
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $searchTerm = '%' . $search . '%';
            $query->whereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", [$searchTerm])
                ->orWhere('email_edu', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm)
                ->orWhere('phone', 'like', $searchTerm)
                ->orWhere('code', 'like', $searchTerm);
        }

        return $query;
    }

    public function getFullNameAttribute(): string
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    public function getDobStringAttribute(): string
    {
        return Carbon::make($this->dob)->format('d/m/Y');
    }
}
