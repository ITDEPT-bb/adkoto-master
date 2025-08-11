<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuctionStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $itemId;
    public $duration;
    public $endTime;

    /**
     * Create a new event instance.
     */
    public function __construct($itemId, $duration)
    {
        $this->itemId = $itemId;
        $this->duration = $duration;
        $this->endTime = now()->addSeconds($duration)->timestamp;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('auction');
    }

    public function broadcastWith()
    {
        return [
            'itemId' => $this->itemId,
            'duration' => $this->duration,
            'endTime' => $this->endTime,
        ];
    }
}
