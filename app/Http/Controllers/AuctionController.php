<?php

namespace App\Http\Controllers;

use App\Models\AuctionItem;
use App\Models\KalakalkotoCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AuctionController extends Controller
{
    public function index()
    {
        $auctionItem = AuctionItem::with(['attachments', 'user', 'category', 'bids'])
            ->orderByDesc('created_at')
            ->where('bidding_type', 'normal')
            ->paginate(9);

        $auctionItem->each(function ($auction) {
            $auction->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        $categories = KalakalkotoCategory::all();

        return Inertia::render('Auction/Index', [
            'normalBiddingItems' => $auctionItem,
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
}
