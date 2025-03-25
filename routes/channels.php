<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('import.progress.{id}', fn ($user, $id) => (int) $user->id === (int) $id);
