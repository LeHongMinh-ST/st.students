<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ReflectStatus;
use App\Enums\ReflectSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property int|null $student_id
 * @property string $title
 * @property ReflectSubject $subject
 * @property int|null $user_id
 * @property ReflectStatus $status
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Semester|null $semester
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reflect newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reflect newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reflect query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reflect whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reflect whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reflect whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reflect whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reflect whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reflect whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reflect whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reflect whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reflect whereUserId($value)
 * @mixin \Eloquent
 */
class Reflect extends Model
{
    protected $fillable = [
        'title',
        'content',
        'status',
        'subject',
        'faculty_id',
        'semester_id',
    ];

    protected $casts = [
        'status' => ReflectStatus::class,
        'subject' => ReflectSubject::class,
    ];

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }
}
