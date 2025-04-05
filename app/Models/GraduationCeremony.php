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
 * @property int $school_year
 * @property int $certification
 * @property string $certification_date
 * @property int|null $faculty_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationCeremony newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationCeremony newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationCeremony query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationCeremony whereCertification($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationCeremony whereCertificationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationCeremony whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationCeremony whereFacultyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationCeremony whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationCeremony whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationCeremony whereSchoolYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationCeremony whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GraduationCeremony extends Model
{
    protected $fillable = [
        'name',
        'school_year',
        'certification',
        'certification_date',
        'faculty_id',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'graduation_ceremony_students')
            ->withTimestamps();
    }
}
