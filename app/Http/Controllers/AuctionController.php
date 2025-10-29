<?php

namespace App\Http\Controllers;

use App\Events\AuctionStarted;
use App\Models\AuctionItem;
use App\Models\AuctionSeller;
use App\Models\Bid;
use App\Models\KalakalkotoCategory;
use App\Models\User;
use App\Models\YoutubeChannelId;
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

        $user = Auth::user();
        $walletBalance = $user->wallet ? $user->wallet->balance : 0;

        $liveBiddingItem = AuctionItem::with(['attachments', 'user', 'category', 'bids'])
            ->select('*', DB::raw('(SELECT MAX(bid_amount) FROM bids WHERE bids.auction_item_id = auction_items.id) as highest_bid'))
            ->where('bidding_type', 'live')
            ->where('auction_ends_at', '>', Carbon::now())
            // ->whereHas('user.wallet', function ($query) {
            //     $query->whereColumn('user_wallets.balance', '>=', 'auction_items.starting_price');
            // })
            ->where('starting_price', '<=', $walletBalance)
            // ->inRandomOrder();
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
            'categories' => $categories,
            'user' => $user,
            'walletBalance' => $walletBalance
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
        $auctionitem = AuctionItem::with([
            'attachments',
            'user',
            'category',
            'bids' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'bids.user'
        ])->findOrFail($id);

        $auctionitem->attachments->each(function ($attachment) {
            $attachment->image_path = asset('storage/' . $attachment->image_path);
        });

        $highBid = Bid::with(['item', 'user'])
            ->where('auction_item_id', $id)
            ->orderBy('bid_amount', 'desc')
            ->first();

        $user = Auth::user();
        $walletBalance = $user->wallet ? $user->wallet->balance : 0;

        return Inertia::render('Auction/Show', [
            'item' => $auctionitem,
            'highBid' => $highBid,
            'bids' => $auctionitem->bids,
            'user' => $user,
            'walletBalance' => $walletBalance
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

        $user = Auth::user();
        $walletBalance = $user->wallet ? $user->wallet->balance : 0;

        return Inertia::render('Auction/Manage', [
            'items' => $items,
            'categories' => $categories,
            'authId' => $userId,
            'user' => $user,
            'walletBalance' => $walletBalance
        ]);
    }

    // public function placeBid($id)
    // {
    //     $auctionItem = AuctionItem::find($id);

    //     if (!$auctionItem) {
    //         return response()->json(['error' => 'Auction item not found'], 404);
    //     }

    //     $bidIncrement = $auctionItem->bid_increment;

    //     $highestBid = $auctionItem->bids()->orderBy('bid_amount', 'desc')->first();
    //     $currentHighestBidAmount = $highestBid ? $highestBid->bid_amount : 0;

    //     $newBidAmount = $currentHighestBidAmount + $bidIncrement;

    //     $userId = auth()->id();
    //     $user = User::find($userId);
    //     $walletBalance = $user->wallet ? $user->wallet->balance : 0;

    //     if ($walletBalance < $newBidAmount) {
    //         return response()->json(['error' => 'Insufficient wallet balance to place this bid'], 403);
    //     }

    //     // Create and save the new bid
    //     $bid = new Bid();
    //     $bid->auction_item_id = $auctionItem->id;
    //     $bid->user_id = $userId;
    //     $bid->bid_amount = $newBidAmount;
    //     $bid->save();

    //     return response()->json(['success' => 'Bid placed successfully', 'new_bid_amount' => $newBidAmount], 200);
    // }
    public function placeBid(Request $request, $id)
    {
        $auctionItem = AuctionItem::find($id);

        if (!$auctionItem) {
            return response()->json(['error' => 'Auction item not found'], 404);
        }

        // Validate bid amount from the request
        $request->validate([
            'bid_amount' => 'required|numeric|min:1',
        ]);

        $newBidAmount = $request->bid_amount;

        // Get highest bid for this item
        $highestBid = $auctionItem->bids()->orderBy('bid_amount', 'desc')->first();
        $currentHighestBidAmount = $highestBid ? $highestBid->bid_amount : $auctionItem->starting_price;

        // Ensure new bid is greater than the highest
        if ($newBidAmount <= $currentHighestBidAmount) {
            return response()->json([
                'error' => 'Your bid must be higher than the current highest bid (' . number_format($currentHighestBidAmount, 2) . ')'
            ], 422);
        }

        $userId = auth()->id();
        $user = User::find($userId);
        $walletBalance = $user->wallet ? $user->wallet->balance : 0;

        // Ensure wallet balance is enough
        // if ($walletBalance < $newBidAmount) {
        if ($walletBalance < 1000) {
            return response()->json([
                'error' => 'Insufficient wallet balance to place this bid. You need to recharge atleast ₱1000 to place a bid'
            ], 403);
        }

        // Create and save the new bid
        $bid = new Bid();
        $bid->auction_item_id = $auctionItem->id;
        $bid->user_id = $userId;
        $bid->bid_amount = $newBidAmount;
        $bid->save();

        event(new \App\Events\BidPlaced($auctionItem->id, $bid));


        return response()->json([
            'success' => 'Bid placed successfully',
            'new_bid_amount' => $newBidAmount
        ], 200);
    }

    public function start(Request $request, AuctionItem $item)
    {
        $request->validate([
            'duration' => 'required|integer|min:10',
            'increment' => 'required|numeric|min:1',
        ]);

        $startingPrice = $item->starting_price;
        // $requiredMinIncrement = $startingPrice * 0.1;
        $requiredMinIncrement = ceil($startingPrice * 0.10);

        if ($request['increment'] < $requiredMinIncrement) {
            return response()->json([
                // 'message' => 'Bid increment must be at least 10% of the starting price (' . number_format($requiredMinIncrement, 2) . ').',
                'message' => 'Bid increment must be at least 10% of the starting price (' . number_format($requiredMinIncrement, 0) . ').',
            ], 422);
        }

        $endTime = now()->addSeconds($request->duration);

        // Save increment
        $item->update([
            'bid_increment' => $request->increment,
            'end_time' => $endTime,
        ]);

        broadcast(new AuctionStarted($item->id, $endTime->timestamp, $item->bid_increment));

        return response()->json(['message' => 'Auction started']);
    }

    public function getLatestBids($id)
    {
        $auctionitem = AuctionItem::with([
            'bids' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'bids.user'
        ])->findOrFail($id);

        $highBid = Bid::with(['item', 'user'])
            ->where('auction_item_id', $id)
            ->orderBy('bid_amount', 'desc')
            ->first();

        return response()->json([
            'bids' => $auctionitem->bids,
            'highBid' => $highBid
        ]);
    }

    public function getLatestBidsList()
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

        $user = Auth::user();
        $walletBalance = $user->wallet ? $user->wallet->balance : 0;

        $liveBiddingItem = AuctionItem::with(['attachments', 'user', 'category', 'bids'])
            ->select('*', DB::raw('(SELECT MAX(bid_amount) FROM bids WHERE bids.auction_item_id = auction_items.id) as highest_bid'))
            ->where('bidding_type', 'live')
            // ->whereHas('user.wallet', function ($query) {
            //     $query->whereColumn('user_wallets.balance', '>=', 'auction_items.starting_price');
            // })
            ->where('starting_price', '<=', $walletBalance)
            ->first();
        // ->inRandomOrder();

        if ($liveBiddingItem) {
            $liveBiddingItem->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        }

        $categories = KalakalkotoCategory::all();

        return response()->json([
            'normalBiddingItems' => $normalBiddingItems,
            'liveBiddingItem' => $liveBiddingItem,
            'categories' => $categories,
            'user' => $user,
            'walletBalance' => $walletBalance
        ]);
    }

    public function viewAllLive()
    {
        $liveBiddingItem = AuctionItem::with(['attachments', 'user', 'category', 'bids'])
            ->select('*', DB::raw('(SELECT MAX(bid_amount) FROM bids WHERE bids.auction_item_id = auction_items.id) as highest_bid'))
            ->orderByDesc('created_at')
            ->where('bidding_type', 'live')
            ->where('auction_ends_at', '>', Carbon::now())
            ->whereHas('user.wallet', function ($query) {
                $query->whereColumn('user_wallets.balance', '>=', 'auction_items.starting_price');
            })
            ->paginate(9);

        $liveBiddingItem->each(function ($auction) {
            $auction->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        $categories = KalakalkotoCategory::all();

        $user = Auth::user();
        $walletBalance = $user->wallet ? $user->wallet->balance : 0;

        return Inertia::render('Auction/Live', [
            'liveBiddingItem' => $liveBiddingItem,
            'categories' => $categories,
            'user' => $user,
            'walletBalance' => $walletBalance
        ]);
    }

    public function getLatestBidsLive()
    {
        $liveBiddingItem = AuctionItem::with(['attachments', 'user', 'category', 'bids'])
            ->select('*', DB::raw('(SELECT MAX(bid_amount) FROM bids WHERE bids.auction_item_id = auction_items.id) as highest_bid'))
            ->orderByDesc('created_at')
            ->where('bidding_type', 'live')
            ->where('auction_ends_at', '>', Carbon::now())
            ->whereHas('user.wallet', function ($query) {
                $query->whereColumn('user_wallets.balance', '>=', 'auction_items.starting_price');
            })
            ->paginate(9);

        $liveBiddingItem->each(function ($auction) {
            $auction->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        return response()->json([
            'liveBiddingItem' => $liveBiddingItem
        ]);
    }

    public function watchStream(Request $request)
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
                $query->orderBy('created_at', 'desc')->limit(1);
            },
            'bids.user'
        ])->where('is_active', true)->first();

        $activeSellerUser = auth()->user();
        $isActiveSeller = AuctionSeller::active()
            ->where('user_id', $activeSellerUser->id)
            ->exists();

        if (!$auctionItem) {
            $user = Auth::user();
            $walletBalance = $user->wallet ? $user->wallet->balance : 0;

            $youtubeChannelId = YoutubeChannelId::latest()->first();

            return Inertia::render('Auction/WatchStream', [
                'noActiveBidding' => true,
                'appId' => $appID,
                'message' => 'No active bidding yet.',
                'user' => $user,
                'is_active_seller' => $isActiveSeller,
                'walletBalance' => $walletBalance,
                'youtubeChannelId' => $youtubeChannelId ? $youtubeChannelId->channel_id : null,
            ]);
        }

        // Process the attachments to get their full image paths
        $auctionItem->attachments->each(function ($attachment) {
            $attachment->image_path = asset('storage/' . $attachment->image_path);
        });

        $highBid = Bid::with(['item', 'user'])
            ->where('auction_item_id', $auctionItem->id)
            ->orderBy('bid_amount', 'desc')
            ->first();

        $user = Auth::user();
        $walletBalance = $user->wallet ? $user->wallet->balance : 0;

        $youtubeChannelId = YoutubeChannelId::latest()->first();

        return Inertia::render('Auction/WatchStream', [
            'item' => $auctionItem,
            'highBid' => $highBid,
            'bids' => $auctionItem->bids,
            'user' => $user,
            'is_active_seller' => $isActiveSeller,
            'appId' => $appID,
            'walletBalance' => $walletBalance,
            'noActiveBidding' => false,
            'youtubeChannelId' => $youtubeChannelId ? $youtubeChannelId->channel_id : null,
        ]);
    }

    public function fetchShowWindowData(Request $request)
    {
        $auctionItem = AuctionItem::with([
            'attachments',
            'user',
            'category',
            'bids' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(1);
            },
            'bids.user'
        ])
            ->where('is_active', true)
            ->latest('updated_at')
            ->first();

        if (!$auctionItem) {
            return response()->json([
                'noActiveBidding' => true,
                'message' => 'No active bidding yet.',
            ]);
        }

        // Process the attachments
        $auctionItem->attachments->each(function ($attachment) {
            $attachment->image_path = asset('storage/' . $attachment->image_path);
        });

        $highBid = Bid::with(['item', 'user'])
            ->where('auction_item_id', $auctionItem->id)
            ->orderBy('bid_amount', 'desc')
            ->first();

        // $user = Auth::user();
        // $walletBalance = $user->wallet ? $user->wallet->balance : 0;

        return response()->json([
            'item' => $auctionItem,
            'highBid' => $highBid,
            'bids' => $auctionItem->bids,
            // 'user' => $user,
            // 'walletBalance' => $walletBalance,
            'noActiveBidding' => false,
        ]);
    }

    public function state($id)
    {
        $item = AuctionItem::findOrFail($id);

        if (!$item->end_time) {
            return response()->json(['end_time' => null, 'is_active' => false]);
        }

        return response()->json([
            'end_time' => $item->end_time,
            'is_active' => now()->lt($item->end_time),
        ]);
    }
}
