<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    ];

    protected $casts = [
        'decision_date' => 'date',
    ];

    /**
     * Determine the warning level for a student.
     */
    public static function getWarningLevel(int $studentId): ?\App\Enums\WarningLevel
    {
        // Lấy 2 đợt cảnh báo gần nhất
        $latestWarnings = StudentWarning::where('student_id', $studentId)
            ->join('warnings', 'student_warnings.warning_id', '=', 'warnings.id')
            ->orderBy('warnings.created_at', 'desc')
            ->take(2)
            ->get();
        // Nếu không có đợt cảnh báo nào, trả về null
        if ($latestWarnings->isEmpty()) {
            return null;
        }

        // Lấy đợt cảnh báo gần nhất
        $latestWarning = $latestWarnings->first();

        // Lấy đợt cảnh báo gần nhất của toàn hệ thống
        $systemLatestWarning = Warning::orderBy('created_at', 'desc')->first();

        // Nếu đợt cảnh báo gần nhất của sinh viên không phải là đợt cảnh báo gần nhất của hệ thống
        // tức là sinh viên không có trong đợt cảnh báo gần nhất, trả về null
        if ($systemLatestWarning && $latestWarning->warning_id !== $systemLatestWarning->id) {
            return null;
        }

        // Nếu chỉ có 1 đợt cảnh báo, trả về cảnh báo mức 1
        if (1 === $latestWarnings->count()) {
            return \App\Enums\WarningLevel::Level1;
        }

        // Nếu có 2 đợt cảnh báo liên tiếp, trả về cảnh báo mức 2
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

        // Đợt cảnh báo gần nhất của hệ thống
        $latestWarning = Warning::orderBy('created_at', 'desc')->first();

        // Nếu đợt cảnh báo hiện tại không phải là đợt cảnh báo gần nhất, trả về 0
        if ($latestWarning && $latestWarning->id !== $this->id) {
            return 0;
        }

        // Đếm số sinh viên chỉ có 1 đợt cảnh báo
        $count = 0;
        foreach ($studentIds as $studentId) {
            $warningCount = StudentWarning::where('student_id', $studentId)
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

        // Đợt cảnh báo gần nhất của hệ thống
        $latestWarning = Warning::orderBy('created_at', 'desc')->first();

        // Nếu đợt cảnh báo hiện tại không phải là đợt cảnh báo gần nhất, trả về 0
        if ($latestWarning && $latestWarning->id !== $this->id) {
            return 0;
        }

        // Đếm số sinh viên có 2 đợt cảnh báo liên tiếp
        $count = 0;
        foreach ($studentIds as $studentId) {
            $warningCount = StudentWarning::where('student_id', $studentId)
                ->count();

            if ($warningCount >= 2) {
                $count++;
            }
        }

        return $count;
    }
}
