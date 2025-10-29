<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuctionStarted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $itemId;
    public $endTime;
    public $bidIncrement;

    /**
     * Create a new event instance.
     */
    public function __construct($itemId, $endTime, $bidIncrement)
    {
        $this->itemId = $itemId;
        $this->endTime = $endTime;
        $this->bidIncrement = $bidIncrement;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('auction');
    }

    public function broadcastWith()
    {
        return [
            'itemId' => $this->itemId,
            'endTime' => $this->endTime,
            'bidIncrement' => $this->bidIncrement,
        ];
    }
}
