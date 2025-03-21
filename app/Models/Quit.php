<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quit extends Model
{
    protected $fillable = [
        'name',
        'semester_id',
        'faculty_id',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_quits')
            ->withPivot(['note_quit'])
            ->withTimestamps();
    }
}
