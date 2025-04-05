<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use App\Enums\SocialPolicyObject;
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
 * @property \Illuminate\Support\Carbon $dob
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
 * @property int|null $student_info_update_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read StudentUpdate|null $studentUpdate
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereCitizenIdentification($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereCountryside($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereEthnic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory wherePermanentResidence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory wherePersonEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory wherePob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereReligion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereSocialPolicyObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereStudentInfoUpdateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereTrainingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentUpdatesHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
