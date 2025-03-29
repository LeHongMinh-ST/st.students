<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StatusImport;
use App\Enums\TypeImport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
