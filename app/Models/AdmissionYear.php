<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property string $admission_year
 * @property string $school_year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ClassGenerate> $generalClasses
 * @property-read int|null $general_classes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionYear newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionYear newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionYear query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionYear whereAdmissionYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionYear whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionYear whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionYear whereSchoolYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionYear whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AdmissionYear extends Model
{
    use HasFactory;

    protected $table = 'admission_years';

    protected $fillable = ['admission_year', 'school_year'];

    // ------------------------ RELATIONS -------------------------//
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'admission_year_id');
    }

    public function generalClasses(): HasMany
    {
        return $this->hasMany(ClassGenerate::class);
    }

    //    public function getStudentCountAttribute(): int
    //    {
    //        return $this->students()
    //            ->where('faculty_id', auth('api')->user()->faculty_id)
    //            ->count();
    //    }
}
