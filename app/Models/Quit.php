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
        'school_year',
        'decision_number',
        'decision_date',
    ];

    protected $casts = [
        'decision_date' => 'date',
    ];

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
        return $this->belongsToMany(Student::class, 'student_quits')
            ->withPivot(['note_quit', 'quit_type'])
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
     * Get the total number of students in this quit decision.
     */
    public function getTotalStudentsAttribute(): int
    {
        return $this->students()->count();
    }

    /**
     * Get the number of students who dropped out voluntarily.
     */
    public function getDropOutStudentsAttribute(): int
    {
        return $this->students()
            ->wherePivot('quit_type', \App\Enums\StudentStatus::ToDropOut->value)
            ->count();
    }

    /**
     * Get the number of students who were temporarily suspended.
     */
    public function getSuspendedStudentsAttribute(): int
    {
        return $this->students()
            ->wherePivot('quit_type', \App\Enums\StudentStatus::TemporarilySuspended->value)
            ->count();
    }

    /**
     * Get the number of students who were expelled.
     */
    public function getExpelledStudentsAttribute(): int
    {
        return $this->students()
            ->wherePivot('quit_type', \App\Enums\StudentStatus::Expelled->value)
            ->count();
    }
}
