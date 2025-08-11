<?php

namespace App\Http\Controllers;

use App\Events\AuctionEvent;
use App\Events\BidPlaced;
use App\Models\AuctionItem;
use App\Models\Bid;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LiveAuctionController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $appID = env('AGORA_APP_ID');
        if (!$appID) {
            return response()->json(['error' => 'Agora App ID is not configured'], 500);
        }

        $auctionItem = AuctionItem::with([
            'attachments',
            'user',
            'category',
            'bids' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'bids.user'
        ])->where('is_active', true)
            ->where('bidding_type', 'live')
            ->get();

        return Inertia::render('Auction/LiveStream', [
            'appId' => $appID,
            'initialItems' => $auctionItem,
        ]);
    }
}
