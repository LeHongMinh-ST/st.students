<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property int $import_history_id
 * @property int $row_number
 * @property string $error_message
 * @property string $record_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ImportHistory $importHistory
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereImportHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereRecordData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereRowNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ImportError extends Model
{
    protected $fillable = [
        'import_history_id',
        'row_number',
        'error_message',
        'record_data',
    ];

    public function importHistory(): BelongsTo
    {
        return $this->belongsTo(ImportHistory::class);
    }
}
