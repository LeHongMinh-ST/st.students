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

/**
 *
 *
 * @property int $id
 * @property StudentStatus $status
 * @property int|null $admission_year
 * @property string|null $person_email
 * @property Gender $gender
 * @property string|null $permanent_residence
 * @property string|null $dob
 * @property string|null $pob
 * @property string|null $address
 * @property string|null $countryside
 * @property TrainingType $training_type
 * @property string|null $phone
 * @property string|null $nationality
 * @property string|null $citizen_identification
 * @property string|null $ethnic
 * @property string|null $religion
 * @property string|null $thumbnail
 * @property SocialPolicyObject $social_policy_object
 * @property string|null $note
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $faculty_id
 * @property string $full_name
 * @property string|null $last_name
 * @property string $code
 * @property string $email
 * @property int|null $admission_year_id
 * @property string|null $email_edu
 * @property string|null $code_import
 * @property string|null $school_year_start
 * @property string|null $school_year_end
 * @property string|null $first_name
 * @property StudentRole $role
 * @property-read AdmissionYear|null $admissionYear
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ClassGenerate> $classes
 * @property-read int|null $classes_count
 * @property-read ClassGenerate|null $currentClass
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Family> $families
 * @property-read int|null $families_count
 * @property-read string $dob_string
 * @property-read \Illuminate\Database\Eloquent\Collection<int, GraduationCeremony> $graduationCeremonies
 * @property-read int|null $graduation_ceremonies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Quit> $quits
 * @property-read int|null $quits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Warning> $warnings
 * @property-read int|null $warnings_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereAdmissionYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereAdmissionYearId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCitizenIdentification($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCodeImport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCountryside($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereEmailEdu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereEthnic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereFacultyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student wherePermanentResidence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student wherePersonEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student wherePob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereReligion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereSchoolYearEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereSchoolYearStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereSocialPolicyObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereTrainingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereUserId($value)
 * @mixin \Eloquent
 */
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

    /**
     * Get the warning level for this student.
     */
    public function getWarningLevelAttribute(): ?\App\Enums\WarningLevel
    {
        return Warning::getWarningLevel($this->id);
    }
}
