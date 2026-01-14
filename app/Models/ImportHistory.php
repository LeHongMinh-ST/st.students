<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StatusImport;
use App\Enums\TypeImport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property string $file_name
 * @property StatusImport $status
 * @property int $total_records
 * @property int $successful_records
 * @property int $faculty_id
 * @property TypeImport $type
 * @property int $created_by
 * @property int $admission_year_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $path
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory whereAdmissionYearId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory whereFacultyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory whereSuccessfulRecords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory whereTotalRecords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ImportHistory extends Model
{
    protected $fillable = [
        'path',
        'file_name',
        'status',
        'total_records',
        'successful_records',
        'faculty_id',
        'type',
        'created_by',
        'admission_year_id',
    ];


    protected $casts = [
        'total_records' => 'integer',
        'successful_records' => 'integer',
        'status' => StatusImport::class,
        'type' => TypeImport::class,
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all errors associated with this import history.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function errors(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ImportError::class);
    }

    /**
     * Check if this import has any errors.
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return $this->total_records > $this->successful_records;
    }
}
