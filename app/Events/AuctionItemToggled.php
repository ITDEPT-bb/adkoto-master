<?php

namespace App\Events;

use App\Models\AuctionItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuctionItemToggled implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $item;

    /**
     * Create a new event instance.
     */
    public function __construct(AuctionItem $item)
    {
        $this->item = $item->load('attachments');
    }

    public function broadcastOn()
    {
        return new Channel('auction-items');
    }

    public function broadcastAs()
    {
        return 'AuctionItemToggled';
    }
}
