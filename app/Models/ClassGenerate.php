<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ClassType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $description
 * @property Status $status
 * @property ClassType $type
 * @property int|null $marjor_id
 * @property int $faculty_id
 * @property int|null $training_industry_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $admission_year_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate whereAdmissionYearId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate whereFacultyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate whereMarjorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate whereTrainingIndustryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassGenerate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClassGenerate extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'code',
        'type',
        'status',
        'major_id',
        'faculty_id',
        'training_industry_id',
        'admission_year_id',
    ];

    protected $casts = [
        'type' => ClassType::class,
        'status' => Status::class,
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'class_students', 'class_id', 'student_id')
            ->withPivot(['start_date', 'end_date', 'status'])
            ->withTimestamps();
    }
}
