<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingIndustry extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'faculty_id',
    ];
}
