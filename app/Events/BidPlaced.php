<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BidPlaced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $itemId;
    public $userId;
    public $name;
    public $amount;
    public $time;

    public function __construct(array $payload)
    {
        $this->itemId = $payload['itemId'];
        $this->userId = $payload['userId'];
        $this->name = $payload['name'];
        $this->amount = $payload['amount'];
        $this->time = $payload['time'];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('auction-channel');
    }
}
