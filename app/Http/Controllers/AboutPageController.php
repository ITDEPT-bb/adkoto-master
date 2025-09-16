<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AuctionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class AboutPageController extends Controller
{
    public function index(Request $request)
    {
        $featuredAds = Advertisement::with(['attachments', 'user', 'category'])
            ->where('featured', true)
            ->orderByDesc('created_at')
            ->get();

        $featuredAds->each(function ($ad) {
            $ad->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        $activeAuctionItems = AuctionItem::with(['attachments', 'user', 'category', 'bids'])
            ->where('auction_ends_at', '>', Carbon::now())
            ->orderByDesc('created_at')
            ->paginate(9);

        $activeAuctionItems->each(function ($auction) {
            $auction->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        return Inertia::render('About', [
            'featuredAds' => $featuredAds,
            'activeAuctions' => $activeAuctionItems,
        ]);
    }
}
