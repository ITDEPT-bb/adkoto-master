<?php

namespace App\Http\Controllers;

use App\Events\AuctionItemToggled;
use App\Http\Resources\AuctionItemResource;
use App\Models\AuctionItem;
use Illuminate\Http\Request;

class LiveAuctionItemController extends Controller
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
        if (!$item->is_active) {
            $alreadyActive = AuctionItem::where('is_active', true)
                ->where('id', '!=', $item->id)
                ->exists();

            if ($alreadyActive) {
                return response()->json([
                    'success' => false,
                    'message' => 'Another auction item is already active.'
                ], 400);
            }
        }

        $item->is_active = !$item->is_active;
        $item->save();

        broadcast(new AuctionItemToggled($item))->toOthers();

        return response()->json(['success' => true]);
    }

}
