<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ClassType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClassGenerate extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'code',
        'type',
        'status',
        'major_id',
        'faculty_id',
        'training_industry_id',
    ];

    protected $casts = [
        'type' => ClassType::class,
        'status' => Status::class,
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'class_students')
            ->withPivot(['start_date', 'end_date', 'status'])
            ->withTimestamps();
    }
}
