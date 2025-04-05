<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property int|null $semester_id
 * @property int|null $faculty_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warning newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warning newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warning query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warning whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warning whereFacultyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warning whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warning whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warning whereSemesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warning whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Warning extends Model
{
    protected $fillable = [
        'name',
        'semester_id',
        'faculty_id',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_warnings')
            ->withPivot(['note'])
            ->withTimestamps();
    }
}
