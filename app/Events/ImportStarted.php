<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportStarted implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $userId;
    public $totalRecords;
    public $importId;

    public function __construct($userId, $totalRecords, $importId)
    {
        $this->userId = $userId;
        $this->totalRecords = $totalRecords;
        $this->importId = $importId;
    }

    public function broadcastOn()
    {
        return new Channel("import.progress.{$this->userId}");
    }

    public function broadcastAs()
    {
        return 'ImportStarted';
    }

    public function broadcastWith()
    {
        return [
            'totalRecords' => $this->totalRecords,
            'importId' => $this->importId,
            'startedAt' => now()->toDateTimeString(),
        ];
    }
}
