<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StudentRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassStudent extends Pivot
{
    use HasFactory;

    protected $table = 'class_students';

    protected $fillable = ['class_id', 'student_id', 'end_date', 'start_date', 'status', 'role'];

    protected $casts = [
        'role' => StudentRole::class,
    ];

    public function classGenerate(): void
    {
        $this->belongsTo(ClassGenerate::class, 'class_id', 'id');
    }
}
