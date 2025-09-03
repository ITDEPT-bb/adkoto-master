<?php

namespace App\Http\Controllers;

use App\Events\SellerToggled;
use App\Models\AuctionSeller;
use App\Models\User;
use Illuminate\Http\Request;

class LiveAuctionSellerController extends Controller
{
    /**
     * List all sellers with linked user info
     */
    // public function index()
    // {
    //     try {
    //         $sellerUserIds = AuctionSeller::pluck('user_id');

    //         $users = User::whereNotIn('id', $sellerUserIds)
    //             ->select('id', 'name', 'surname', 'email', 'avatar_path')
    //             ->orderBy('name')
    //             ->get();

    //         return response()->json($users);
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'error' => 'Failed to fetch users',
    //             'details' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // /**
    //  * Add a seller (link user → seller)
    //  */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'user_id' => 'required|exists:users,id|unique:auction_sellers,user_id',
    //     ]);

    //     $seller = AuctionSeller::create([
    //         'user_id' => $validated['user_id'],
    //         'is_active' => false,
    //     ]);

    //     return response()->json([
    //         'message' => 'Seller added successfully.',
    //         'seller' => $seller->load('user:id,name,surname,email,avatar_path'),
    //     ], 201);
    // }

    // public function list()
    // {
    //     return response()->json(
    //         AuctionSeller::with('user:id,name,surname,email,avatar_path')
    //             ->orderBy('id')
    //             ->get()
    //     );
    // }

    // /**
    //  * Only one active seller allowed at a time.
    //  */
    // public function toggleActive(AuctionSeller $seller)
    // {
    //     if ($seller->is_active) {
    //         // deactivate seller
    //         $seller->update(['is_active' => false]);
    //         return response()->json([
    //             'message' => $seller->user->name . ' has been deactivated.',
    //             'seller' => $seller->load('user:id,name,email,avatar_path'),
    //         ]);
    //     }

    //     // deactivate all other sellers
    //     AuctionSeller::where('is_active', true)->update(['is_active' => false]);

    //     // activate selected seller
    //     $seller->update(['is_active' => true]);

    //     return response()->json([
    //         'message' => $seller->user->name . ' is now the active seller.',
    //         'seller' => $seller->load('user:id,name,email,avatar_path'),
    //     ]);
    // }

    // /**
    //  * Remove a seller
    //  */
    // public function destroy($id)
    // {
    //     try {
    //         $seller = AuctionSeller::findOrFail($id);
    //         $seller->delete();

    //         return response()->json([
    //             'message' => 'Seller removed successfully.'
    //         ]);
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'error' => 'Failed to remove seller',
    //             'details' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function users(Request $request)
    {
        $search = $request->query('search');

        $query = User::query()
            ->whereDoesntHave('auctionSeller')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });

        return $query->paginate(10);
    }

    public function sellers(Request $request)
    {
        $search = $request->query('search');

        $query = AuctionSeller::with('user')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('surname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        return $query->paginate(10);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $seller = AuctionSeller::create([
            'user_id' => $request->user_id,
            'is_active' => false,
        ]);

        return response()->json([
            'message' => 'Seller added successfully.',
            'seller' => $seller,
        ]);
    }

    public function toggle(AuctionSeller $seller)
    {
        if (!$seller->is_active) {
            AuctionSeller::query()->update(['is_active' => false]);
        }

        $seller->update(['is_active' => !$seller->is_active]);

        // Broadcast to all clients
        broadcast(new SellerToggled($seller))->toOthers();

        return response()->json([
            'message' => $seller->is_active
                ? 'Seller activated.'
                : 'Seller deactivated.',
        ]);
    }

    public function destroy(AuctionSeller $seller)
    {
        $seller->delete();

        return response()->json([
            'message' => 'Seller removed successfully.',
        ]);
    }
}
