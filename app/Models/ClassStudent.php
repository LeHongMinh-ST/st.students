<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StudentRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 *
 *
 * @property int $id
 * @property int $class_id
 * @property int $student_id
 * @property StudentRole $role
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $start_year
 * @property string|null $end_year
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassStudent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassStudent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassStudent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassStudent whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassStudent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassStudent whereEndYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassStudent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassStudent whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassStudent whereStartYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassStudent whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassStudent whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassStudent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClassStudent extends Pivot
{
    use HasFactory;

    protected $table = 'class_students';

    protected $fillable = ['class_id', 'student_id', 'end_year', 'start_year', 'status', 'role'];

    protected $casts = [
        'role' => StudentRole::class,
    ];

    public function classGenerate(): void
    {
        $this->belongsTo(ClassGenerate::class, 'class_id', 'id');
    }
}
