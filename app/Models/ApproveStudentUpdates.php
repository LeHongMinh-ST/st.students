<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StudentUpdateStatus;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 *
 *
 * @property int $id
 * @property string $approveable_type
 * @property int $approveable_id
 * @property StudentUpdateStatus $status
 * @property int|null $student_info_updates_id
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|Eloquent $approveable
 * @property-read StudentUpdate|null $studentUpdate
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApproveStudentUpdates newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApproveStudentUpdates newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApproveStudentUpdates query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApproveStudentUpdates whereApproveableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApproveStudentUpdates whereApproveableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApproveStudentUpdates whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApproveStudentUpdates whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApproveStudentUpdates whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApproveStudentUpdates whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApproveStudentUpdates whereStudentInfoUpdatesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApproveStudentUpdates whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ApproveStudentUpdates extends Model
{
    protected $fillable = [
        'approveable_type',
        'approveable_id',
        'status',
        'student_info_updates_id',
        'note',
    ];

    protected $casts = [
        'status' => StudentUpdateStatus::class,
    ];

    public function approveable(): MorphTo
    {
        return $this->morphTo();
    }

    public function studentUpdate(): BelongsTo
    {
        return $this->belongsTo(StudentUpdate::class, 'student_info_updates_id');
    }
}
