<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StudentInfoUpdateStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ApproveStudentUpdates extends Model
{
    protected $fillable = [
        'approveable_type',
        'approveable_id',
        'status',
        'student_info_updates_id',
        'note',
    ];

    protected $casts = [
        'status' => StudentInfoUpdateStatus::class,
    ];

    public function approveable(): MorphTo
    {
        return $this->morphTo();
    }

    public function studentUpdate(): BelongsTo
    {
        return $this->belongsTo(StudentUpdate::class, 'student_info_updates_id');
    }
}
