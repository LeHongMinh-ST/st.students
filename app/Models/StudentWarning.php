<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentWarning extends Model
{
    protected $table = 'student_warnings';

    protected $fillable = [
        'student_id',
        'warning_id',
        'note',
        'gpa',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function warning(): BelongsTo
    {
        return $this->belongsTo(Warning::class);
    }
}
