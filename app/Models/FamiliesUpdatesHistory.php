<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FamilyRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property FamilyRelationship|null $relationship
 * @property string $full_name
 * @property string $job
 * @property string $phone
 * @property int $student_info_updates_history_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read StudentUpdatesHistory|null $studentUpdate
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdatesHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdatesHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdatesHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdatesHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdatesHistory whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdatesHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdatesHistory whereJob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdatesHistory wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdatesHistory whereRelationship($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdatesHistory whereStudentInfoUpdatesHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdatesHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FamiliesUpdatesHistory extends Model
{
    protected $table = 'families_updates_history';

    protected $fillable = [
        'relationship',
        'full_name',
        'job',
        'phone',
        'student_info_update_id',
    ];

    protected $casts = [
        'relationship' => FamilyRelationship::class,
    ];

    public function studentUpdate(): BelongsTo
    {
        return $this->belongsTo(StudentUpdatesHistory::class, 'student_info_update_id');
    }
}
