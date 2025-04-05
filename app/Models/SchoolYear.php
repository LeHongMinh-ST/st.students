<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property string $start_year
 * @property string $end_year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Semester> $semesters
 * @property-read int|null $semesters_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolYear newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolYear newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolYear query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolYear whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolYear whereEndYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolYear whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolYear whereStartYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolYear whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SchoolYear extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class);
    }
}
