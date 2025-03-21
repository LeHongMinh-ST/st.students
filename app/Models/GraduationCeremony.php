<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GraduationCeremony extends Model
{
    protected $fillable = [
        'name',
        'school_year',
        'certification',
        'certification_date',
        'faculty_id',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'graduation_ceremony_students')
            ->withTimestamps();
    }
}
