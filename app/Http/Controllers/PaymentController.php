<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KalakalkotoItem;
use App\Models\AuctionItem;
use App\Models\KalakalkotoCategory;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        $item = KalakalkotoItem::findOrFail($request->input('item_id'));
        $auctionType = $request->input('auction_type');

        $amount = $auctionType == 'normal' ? 30000 : 50000; // Amount in cents
        $lineItems = [
            [
                'amount' => $amount,
                'currency' => 'PHP',
                'description' => "Payment to move {$item->name} for auction",
                // 'name' => $item->name,
                'name' => "Move to auction '{$item->name}'",
                'quantity' => 1,
            ],
        ];

        $checkout = Http::withBasicAuth(env('PAYMONGO_SECRET_KEY'), '')
            ->post('https://api.paymongo.com/v1/checkout_sessions', [
                'data' => [
                    'attributes' => [
                        'line_items' => $lineItems,
                        'payment_method_types' => ['card', 'gcash', 'paymaya'],
                        'success_url' => route('payment.success', ['itemId' => $item->id, 'auction_type' => $auctionType]), // Pass auction_type in success_url
                        'cancel_url' => route('payment.cancel'),
                    ],
                ],
            ]);

        // Log the entire response for debugging
        // \Log::info('PayMongo Checkout Response:', $checkout->json());

        if ($checkout->successful()) {
            $checkoutUrl = $checkout['data']['attributes']['checkout_url'] ?? null;

            if ($checkoutUrl) {
                return response()->json(['checkout_url' => $checkoutUrl]);
            } else {
                return response()->json(['error' => 'Checkout URL not found'], 500);
            }
        } else {
            // Log the error response for debugging
            // \Log::error('PayMongo Checkout Error:', $checkout->json());

            return response()->json(['error' => 'Failed to create checkout session'], 500);
        }
    }

    public function paymentSuccess($itemId, Request $request)
    {
        $item = KalakalkotoItem::findOrFail($itemId);

        // Insert item into auction_items
        $auctionItem = AuctionItem::create([
            'user_id' => $item->user_id,
            'category_id' => $item->category_id,
            'name' => $item->name,
            'description' => $item->description,
            'location' => $item->location,
            'starting_price' => $item->price,
            'auction_ends_at' => now()->addDays(7),
            'bidding_type' => $request->input('auction_type'),
        ]);

        $attachments = $item->attachments;
        foreach ($attachments as $attachment) {
            DB::table('auction_item_attachments')->insert([
                'auction_id' => $auctionItem->id,
                'image_path' => $attachment->image_path,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $item->delete();

        return redirect()->route('auction.show', ['id' => $auctionItem->id])->with('status', 'Item moved to auction!');
    }


    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        if ($payload['data']['attributes']['status'] === 'paid') {
            $checkoutSessionId = $payload['data']['id'];
            $auctionItem = AuctionItem::where('checkout_session_id', $checkoutSessionId)->first();

            if ($auctionItem) {
                $auctionItem->status = 'active';
                $auctionItem->save();
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function paymentCancel()
    {
        return Inertia::render('Kalakalkoto/Cancel', []);
    }
}
