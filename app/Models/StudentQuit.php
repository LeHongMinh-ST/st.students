<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentQuit extends Model
{
    protected $table = 'student_quits';

    protected $fillable = [
        'student_id',
        'quit_id',
        'note_quit',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function quit(): BelongsTo
    {
        return $this->belongsTo(Quit::class);
    }
}
