<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackReply extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'feedback_replies';

    protected $fillable = [
        'feedback_id',
        'user_id',
        'content',
    ];

    /**
     * Lấy phản ánh mà phản hồi này thuộc về
     */
    public function feedback(): BelongsTo
    {
        return $this->belongsTo(Feedback::class);
    }

    /**
     * Lấy người dùng tạo phản hồi
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
