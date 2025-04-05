<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use App\Enums\SocialPolicyObject;
use App\Enums\StudentUpdateStatus;
use App\Enums\TrainingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property string $person_email
 * @property Gender $gender
 * @property string $permanent_residence
 * @property string $dob
 * @property string $pob
 * @property string $address
 * @property string $countryside
 * @property TrainingType $training_type
 * @property string $phone
 * @property string $nationality
 * @property string $citizen_identification
 * @property string $ethnic
 * @property string $religion
 * @property string|null $thumbnail
 * @property SocialPolicyObject $social_policy_object
 * @property string|null $note
 * @property string $change_column
 * @property int $student_id
 * @property StudentUpdateStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Student|null $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereChangeColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereCitizenIdentification($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereCountryside($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereEthnic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate wherePermanentResidence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate wherePersonEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate wherePob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereReligion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereSocialPolicyObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereTrainingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
