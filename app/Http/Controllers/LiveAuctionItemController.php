<?php

namespace App\Http\Controllers;

use App\Events\AuctionItemToggled;
use App\Http\Resources\AuctionItemResource;
use App\Models\AuctionItem;
use App\Models\Bid;
use Illuminate\Http\Request;

class LiveAuctionItemController extends Controller
{
    // public function index()
    // {
    //     $items = AuctionItem::select('id', 'name', 'is_active')
    //         ->with('attachments')
    //         ->where('bidding_type', 'live')
    //         ->where('auction_ends_at', '>', now())
    //         ->get();

    //     return AuctionItemResource::collection($items);
    // }
    // public function index()
    // {
    //     $userId = auth()->id();

    //     $items = AuctionItem::select('id', 'name', 'is_active')
    //         ->with('attachments')
    //         ->where('user_id', $userId)
    //         ->where('bidding_type', 'live')
    //         ->where('auction_ends_at', '>', now())
    //         ->get();

    //     return AuctionItemResource::collection($items);
    // }
    public function index()
    {
        $user = auth()->user();

        $items = AuctionItem::select('id', 'name', 'is_active')
            ->with('attachments')
            ->where('bidding_type', 'live')
            ->where('auction_ends_at', '>', now());

        // If not admin, restrict to their own items
        if (!$user->is_filament_admin) {
            $items->where('user_id', $user->id);
        }

        return AuctionItemResource::collection($items->get());
    }



    // public function toggleActive(AuctionItem $item)
    // {
    //     if (!$item->is_active) {
    //         $alreadyActive = AuctionItem::where('is_active', true)
    //             ->where('id', '!=', $item->id)
    //             ->exists();

    //         if ($alreadyActive) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Another auction item is already active.'
    //             ], 400);
    //         }
    //     }

    //     $item->is_active = !$item->is_active;
    //     $item->save();

    //     broadcast(new AuctionItemToggled($item))->toOthers();

    //     return response()->json(['success' => true]);
    // }


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
