<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FamilyRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property FamilyRelationship|null $relationship
 * @property string|null $full_name
 * @property string|null $job
 * @property string|null $phone
 * @property int|null $student_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Student|null $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereJob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereRelationship($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Family extends Model
{
    protected $fillable = [
        'relationship',
        'full_name',
        'job',
        'phone',
        'student_id',
    ];

    protected $casts = [
        'relationship' => FamilyRelationship::class,
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
