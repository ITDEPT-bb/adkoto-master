<?php

namespace App\Http\Controllers;

use App\Models\AuctionItem;
use App\Models\Bid;
use App\Models\KalakalkotoCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AuctionController extends Controller
{
    public function index()
    {
        $normalBiddingItems = AuctionItem::with(['attachments', 'user', 'category', 'bids'])
            ->select('*', DB::raw('(SELECT MAX(bid_amount) FROM bids WHERE bids.auction_item_id = auction_items.id) as highest_bid'))
            ->orderByDesc('created_at')
            ->where('bidding_type', 'normal')
            ->where('auction_ends_at', '>', Carbon::now())
            ->paginate(9);

        $normalBiddingItems->each(function ($auction) {
            $auction->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        $liveBiddingItem = AuctionItem::with(['attachments', 'user', 'category', 'bids'])
            ->select('*', DB::raw('(SELECT MAX(bid_amount) FROM bids WHERE bids.auction_item_id = auction_items.id) as highest_bid'))
            ->where('bidding_type', 'live')
            ->inRandomOrder()
            ->first();

        if ($liveBiddingItem) {
            $liveBiddingItem->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        }

        $categories = KalakalkotoCategory::all();

        return Inertia::render('Auction/Index', [
            'normalBiddingItems' => $normalBiddingItems,
            'liveBiddingItem' => $liveBiddingItem,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $categories = KalakalkotoCategory::all();
        return Inertia::render('Auction/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:kalakalkoto_categories,id',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'starting_price' => 'required|numeric|min:0',
            'bidding_type' => ['required', Rule::in(['normal', 'live'])],
            'auction_ends_at' => 'required|date',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Create the item
        $auctionitem = AuctionItem::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'location' => $request->location,
            'starting_price' => $request->starting_price,
            'bidding_type' => $request->bidding_type,
            'auction_ends_at' => $request->auction_ends_at,
        ]);

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('auction_images', 'public');
                $auctionitem->attachments()->create([
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('auction')->with('success', 'Item created successfully.');
    }

    public function show($id)
    {
        $auctionitem = AuctionItem::with(['attachments', 'user', 'category', 'bids.user'])
            ->findOrFail($id);

        $auctionitem->attachments->each(function ($attachment) {
            $attachment->image_path = asset('storage/' . $attachment->image_path);
        });

        $highBid = Bid::with(['item', 'user'])
            ->where('auction_item_id', $id)
            ->orderBy('bid_amount', 'desc')
            ->first();

        return Inertia::render('Auction/Show', [
            'item' => $auctionitem,
            'highBid' => $highBid,
            'bids' => $auctionitem->bids
        ]);
    }

    public function showUserItems()
    {
        $userId = Auth::id();

        $items = AuctionItem::where('user_id', $userId)
            ->select('*', DB::raw('(SELECT MAX(bid_amount) FROM bids WHERE bids.auction_item_id = auction_items.id) as highest_bid'))
            ->with(['attachments', 'user', 'category'])
            ->orderByDesc('created_at')
            ->paginate(12);

        $items->each(function ($item) {
            $item->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        $categories = KalakalkotoCategory::all();

        return Inertia::render('Auction/Manage', [
            'items' => $items,
            'categories' => $categories,
            'authId' => $userId
        ]);
    }
}
