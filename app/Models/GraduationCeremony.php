<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RankGraduate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $school_year
 * @property string $certification
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

    protected $casts = [
        'certification_date' => 'date',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'graduation_ceremony_students')
            ->withPivot(['gpa', 'rank', 'email','industry_code','industry_name', 'citizen_identification', 'phone_number'])
            ->withTimestamps();
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $searchTerm = '%' . $search . '%';
            $query->where(function ($q) use ($searchTerm): void {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('school_year', 'like', $searchTerm)
                    ->orWhere('certification', 'like', $searchTerm);
            });
        }

        return $query;
    }

    /**
     * Get the total number of students in this graduation ceremony.
     */
    public function getTotalStudentsAttribute(): int
    {
        return $this->students()->count();
    }

    /**
     * Get the number of students with excellent rank in this graduation ceremony.
     */
    public function getExcellentStudentsAttribute(): int
    {
        return $this->students()
            ->wherePivot('rank', RankGraduate::Excellent->value)
            ->count();
    }

    /**
     * Get the number of students with very good rank in this graduation ceremony.
     */
    public function getVeryGoodStudentsAttribute(): int
    {
        return $this->students()
            ->wherePivot('rank', RankGraduate::VeryGood->value)
            ->count();
    }

    /**
     * Get the number of students with good rank in this graduation ceremony.
     */
    public function getGoodStudentsAttribute(): int
    {
        return $this->students()
            ->wherePivot('rank', RankGraduate::Good->value)
            ->count();
    }

    /**
     * Get the number of students with average rank in this graduation ceremony.
     */
    public function getAverageStudentsAttribute(): int
    {
        return $this->students()
            ->wherePivot('rank', RankGraduate::Average->value)
            ->count();
    }
}
