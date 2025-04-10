<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostNotification extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'faculty_id',
        'title',
        'content',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
