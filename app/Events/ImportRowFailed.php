<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportRowFailed implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $userId;
    public $importId;
    public $rowNumber;
    public $errorMessage;
    public $rowData;

    public function __construct($userId, $importId, $rowNumber, $errorMessage, $rowData)
    {
        $this->userId = $userId;
        $this->importId = $importId;
        $this->rowNumber = $rowNumber;
        $this->errorMessage = $errorMessage;
        $this->rowData = $rowData;
    }

    public function broadcastOn()
    {
        return new Channel("import.progress.{$this->userId}");
    }

    public function broadcastAs()
    {
        return 'ImportRowFailed';
    }

    public function broadcastWith()
    {
        return [
            'importId' => $this->importId,
            'rowNumber' => $this->rowNumber,
            'errorMessage' => $this->errorMessage,
            'rowData' => $this->rowData,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
