<?php

namespace App\Http\Controllers;

use App\Events\AuctionUpdated;
use App\Models\AuctionItem;
use Illuminate\Http\Request;

class AuctionHostController extends Controller
{
    public function setActive(AuctionItem $item)
    {
        // $this->authorize('manage', AuctionItem::class);

        AuctionItem::where('status', 'active')->update(['status' => 'upcoming']);
        $item->update(['status' => 'active']);

        event(new AuctionUpdated('active-item', $item));
        return response()->json(['ok' => true]);
    }

    public function setNext(AuctionItem $item)
    {
        // $this->authorize('manage', AuctionItem::class);

        AuctionItem::where('status', 'next')->update(['status' => 'upcoming']);
        $item->update(['status' => 'next']);

        event(new AuctionUpdated('next-item', $item));
        return response()->json(['ok' => true]);
    }

    public function nextItem()
    {
        // $this->authorize('manage', AuctionItem::class);

        $next = AuctionItem::where('status', 'upcoming')->orderBy('id')->first();
        if (!$next)
            return response()->json(['ok' => false, 'message' => 'No more items'], 422);

        AuctionItem::where('status', 'active')->update(['status' => 'upcoming']);
        $next->update(['status' => 'active']);

        event(new AuctionUpdated('next-item', $next));
        return response()->json(['ok' => true]);
    }

    public function endAuction()
    {
        // $this->authorize('manage', AuctionItem::class);

        AuctionItem::query()->update(['status' => 'sold']);
        event(new AuctionUpdated('end', null));
        return response()->json(['ok' => true]);
    }
}
