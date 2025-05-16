<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property string $start_year
 * @property string $end_year
 * @property int $semester
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Quit> $quits
 * @property-read int|null $quits_count
 * @property-read SchoolYear|null $schoolYear
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Warning> $warnings
 * @property-read int|null $warnings_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereEndYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereStartYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Semester extends Model
{
    protected $fillable = [
        'semester',
        'school_year_id',
    ];

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function warnings(): HasMany
    {
        return $this->hasMany(Warning::class);
    }

    public function quits(): HasMany
    {
        return $this->hasMany(Quit::class);
    }
}
