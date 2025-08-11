<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuctionUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $payload;

    /**
     * Create a new event instance.
     */
    public function __construct(string $type, $payload)
    {
        $this->type = $type;
        $this->payload = $payload;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('auction-room');
    }

    public function broadcastAs()
    {
        return 'AuctionUpdated';
    }
}
