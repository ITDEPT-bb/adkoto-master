<?php

namespace App\Http\Controllers;

use App\Events\BidPlaced;
use App\Events\AuctionUpdated;
use App\Models\AuctionItem;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:auction_items,id',
            'bid_amount' => 'required|numeric|min:0.01',
        ]);

        $item = AuctionItem::findOrFail($request->item_id);

        $min = $item->current_price ?? $item->starting_price;
        // if ($request->amount <= $min) {
        //     return response()->json(['message' => 'Bid must be higher than current bid.'], 422);
        // }
        $requiredMin = $min + ($min * 0.1); // 10% increment

        if ($request->bid_amount < $requiredMin) {
            return response()->json([
                'error' => "Bid must be at least 10% higher than ₱" . number_format($min, 2),
            ], 422);
        }

        $bid = Bid::create([
            'auction_item_id' => $item->id,
            'user_id' => $request->user()->id,
            'bid_amount' => $request->amount,
        ]);

        $item->update(['current_price' => $request->amount]);

        $payload = [
            'itemId' => $item->id,
            'userId' => $request->user()->id,
            'name' => $request->user()->name,
            'amount' => (float) $request->amount,
            'time' => now()->format('H:i:s'),
        ];

        event(new BidPlaced($item->id, $bid));
        event(new AuctionUpdated('bid', $payload));

        return response()->json(['ok' => true]);
    }
}
