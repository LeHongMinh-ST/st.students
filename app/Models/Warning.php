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
        'school_year',
        'decision_number',
        'decision_date',
    ];

    protected $casts = [
        'decision_date' => 'date',
    ];

    /**
     * Determine the warning level for a student.
     */
    public static function getWarningLevel(int $studentId): ?\App\Enums\WarningLevel
    {
        $warningCount = StudentWarning::where('student_id', $studentId)
            ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)')
            ->count();

        if (0 === $warningCount) {
            return null;
        }

        if (1 === $warningCount) {
            return \App\Enums\WarningLevel::Level1;
        }

        return \App\Enums\WarningLevel::Level2;
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_warnings')
            ->withPivot(['note', 'gpa'])
            ->withTimestamps();
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $searchTerm = '%' . $search . '%';
            $query->where(function ($q) use ($searchTerm): void {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('school_year', 'like', $searchTerm)
                    ->orWhere('decision_number', 'like', $searchTerm);
            });
        }

        return $query;
    }

    /**
     * Get the total number of students in this warning.
     */
    public function getTotalStudentsAttribute(): int
    {
        return $this->students()->count();
    }

    /**
     * Get the number of students with level 1 warning.
     */
    public function getLevel1StudentsAttribute(): int
    {
        $studentIds = $this->students()->pluck('students.id')->toArray();

        if (empty($studentIds)) {
            return 0;
        }

        // Count students who have only one warning in the last year
        $count = 0;
        foreach ($studentIds as $studentId) {
            $warningCount = StudentWarning::where('student_id', $studentId)
                ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)')
                ->count();

            if (1 === $warningCount) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get the number of students with level 2 warning.
     */
    public function getLevel2StudentsAttribute(): int
    {
        $studentIds = $this->students()->pluck('students.id')->toArray();

        if (empty($studentIds)) {
            return 0;
        }

        // Count students who have two or more warnings in the last year
        $count = 0;
        foreach ($studentIds as $studentId) {
            $warningCount = StudentWarning::where('student_id', $studentId)
                ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)')
                ->count();

            if ($warningCount >= 2) {
                $count++;
            }
        }

        return $count;
    }
}
