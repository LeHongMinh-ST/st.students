<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property Status $status
 * @property-read \App\Models\Faculty|null $faculty
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department query()
 *
 * @mixin \Eloquent
 */
class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'status',
        'faculty_id',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
