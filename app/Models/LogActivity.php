<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $user_name
 * @property string $action
 * @property string|null $details
 * @property string|null $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogActivity whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogActivity whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogActivity whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogActivity whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogActivity whereUserName($value)
 * @mixin \Eloquent
 */
class LogActivity extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'user_name', 'action', 'details', 'ip_address', 'faculty_id'];
}
