<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FamilyRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamiliesUpdatesHistory extends Model
{
    protected $table = 'families_updates_history';

    protected $fillable = [
        'relationship',
        'full_name',
        'job',
        'phone',
        'student_info_update_id',
    ];

    protected $casts = [
        'relationship' => FamilyRelationship::class,
    ];

    public function studentUpdate(): BelongsTo
    {
        return $this->belongsTo(StudentUpdatesHistory::class, 'student_info_update_id');
    }
}
