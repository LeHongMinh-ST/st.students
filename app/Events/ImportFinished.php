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
        $errors = [],
        $timeElapsed = null
    ) {
        $this->userId = $userId;
        $this->importId = $importId;
        $this->status = $status;
        $this->successCount = $successCount;
        $this->errorCount = $errorCount;
        $this->errors = $errors;
        $this->timeElapsed = $timeElapsed ?? 'N/A';
    }

    public function broadcastOn()
    {
        // return new Channel("import.progress.{$this->userId}");
        return new Channel("test-event");
    }

    public function broadcastAs()
    {
        return 'ImportFinished';
    }

    public function broadcastWith()
    {
        return [
            'importId' => $this->importId,
            'status' => $this->status,
            'successCount' => $this->successCount,
            'errorCount' => $this->errorCount,
            'errors' => $this->errors,
            'timeElapsed' => $this->timeElapsed,
            'finishedAt' => now()->toDateTimeString(),
        ];
    }
}
