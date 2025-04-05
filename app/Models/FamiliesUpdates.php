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
 * @property int|null $student_info_update_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read StudentUpdate|null $studentUpdate
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdates newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdates newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdates query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdates whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdates whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdates whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdates whereJob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdates wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdates whereRelationship($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdates whereStudentInfoUpdateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamiliesUpdates whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FamiliesUpdates extends Model
{
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
        return $this->belongsTo(StudentUpdate::class, 'student_info_update_id');
    }
}
