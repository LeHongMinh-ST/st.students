<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportFinished implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $userId;
    public $importId;
    public $status;
    public $successCount;
    public $errorCount;
    public $errors;
    public $timeElapsed;

    public function __construct(
        $userId,
        $importId,
        $status,
        $successCount,
        $errorCount,
        $timeElapsed = null
    ) {
        $this->userId = $userId;
        $this->importId = $importId;
        $this->status = $status;
        $this->successCount = $successCount;
        $this->errorCount = $errorCount;
        $this->timeElapsed = $timeElapsed ?? 'N/A';
    }

    public function broadcastOn()
    {
        return new Channel("import.progress.{$this->userId}");
    }

    public function broadcastAs()
    {
        return 'import.finished';
    }

    public function broadcastWith()
    {
        return [
            'importId' => $this->importId,
            'status' => $this->status,
            'successCount' => $this->successCount,
            'errorCount' => $this->errorCount,
            'timeElapsed' => $this->timeElapsed,
            'finishedAt' => now()->toDateTimeString(),
        ];
    }
}
