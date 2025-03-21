<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'status',
        'faculty_id',
    ];

    protected $casts = [
        'status' => PostStatus::class,
    ];
}
