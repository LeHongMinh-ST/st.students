<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassAssign extends Model
{
    protected $fillable = [
        'class_id',
        'teacher_id',
        'sub_teacher_id',
        'year',
        'status',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function classGenerate(): BelongsTo
    {
        return $this->belongsTo(ClassGenerate::class, 'class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sub_teacher_id');
    }
}
