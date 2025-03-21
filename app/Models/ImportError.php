<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
