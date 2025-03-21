<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ReflectStatus;
use App\Enums\ReflectSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reflect extends Model
{
    protected $fillable = [
        'title',
        'content',
        'status',
        'subject',
        'faculty_id',
        'semester_id',
    ];

    protected $casts = [
        'status' => ReflectStatus::class,
        'subject' => ReflectSubject::class,
    ];

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }
}
