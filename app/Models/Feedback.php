<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FeedbackStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'feedbacks';

    protected $fillable = [
        'student_id',
        'class_id',
        'title',
        'content',
        'status',
        'faculty_id',
    ];

    protected $casts = [
        'status' => FeedbackStatus::class,
    ];

    /**
     * Lấy sinh viên tạo phản ánh
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Lấy lớp học của phản ánh
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassGenerate::class, 'class_id');
    }

    /**
     * Lấy danh sách phản hồi cho phản ánh
     */
    public function replies(): HasMany
    {
        return $this->hasMany(FeedbackReply::class);
    }

    /**
     * Lấy khoa của phản ánh
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }
}
