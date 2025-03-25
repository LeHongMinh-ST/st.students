<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportProgressUpdated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $userId;
    public $importId;
    public $progress;
    public $processedRecords;
    public $successCount;
    public $errorCount;
    public $currentRow;

    public function __construct(
        $userId,
        $importId,
        $progress,
        $processedRecords,
        $successCount,
        $errorCount,
        $currentRow = null
    ) {
        $this->userId = $userId;
        $this->importId = $importId;
        $this->progress = $progress;
        $this->processedRecords = $processedRecords;
        $this->successCount = $successCount;
        $this->errorCount = $errorCount;
        $this->currentRow = $currentRow;
    }

    public function broadcastOn()
    {
        return new Channel("import.progress.{$this->userId}");
    }

    public function broadcastAs()
    {
        return 'ImportProgressUpdated';
    }

    public function broadcastWith()
    {
        return [
            'importId' => $this->importId,
            'progress' => $this->progress,
            'processedRecords' => $this->processedRecords,
            'successCount' => $this->successCount,
            'errorCount' => $this->errorCount,
            'currentRow' => $this->currentRow,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
