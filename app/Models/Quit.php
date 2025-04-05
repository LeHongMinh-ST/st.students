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
 * @property int $semester_id
 * @property int|null $faculty_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quit whereFacultyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quit whereSemesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Quit extends Model
{
    protected $fillable = [
        'name',
        'semester_id',
        'faculty_id',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_quits')
            ->withPivot(['note_quit'])
            ->withTimestamps();
    }
}
