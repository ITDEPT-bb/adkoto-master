<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuctionItemResource;
use App\Models\AuctionItem;
use Illuminate\Http\Request;

class AuctionItemController extends Controller
{
    public function index()
    {
        $items = AuctionItem::select('id', 'name', 'is_active')
            ->with('attachments')
            ->where('bidding_type', 'live')
            ->where('auction_ends_at', '>', now())
            ->get();

        return AuctionItemResource::collection($items);
    }

    public function toggleActive(AuctionItem $item)
    {
        $item->is_active = !$item->is_active;
        $item->save();

        return response()->json(['success' => true]);
    }
}
