<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class RechargeWalletController extends Controller
{
    public function createRechargeSession(Request $request)
    {
        $user = $request->user();

        // Ensure the amount is multiplied by 100 and cast to integer
        // $amount = (int) ($request->input('amount') * 100);
        $originalAmount = (float) $request->input('amount');
        $originalAmountCents = (int) ($originalAmount * 100);

        $chargePercentage = 0.05;
        $chargeAmount = $originalAmount * $chargePercentage;

        $finalAmount = $originalAmount + $chargeAmount;

        $amount = (int) ($finalAmount * 100);

        // Ensure that the amount is valid
        if ($amount <= 0) {
            return response()->json(['error' => 'Invalid amount'], 400);
        }

        // Create a new wallet transaction with 'pending' status
        $transaction = WalletTransaction::create([
            'user_id' => $user->id,
            // 'amount' => $amount,
            'amount' => $originalAmountCents,
            'transaction_type' => 'recharge',
            'status' => 'pending',
        ]);

        // Prepare line items for the PayMongo checkout session
        $lineItems = [
            [
                'amount' => $amount,
                'currency' => 'PHP',
                'description' => "Wallet recharge for {$user->name}",
                'name' => "Wallet Recharge",
                'quantity' => 1,
            ],
        ];

        // Create checkout session with PayMongo
        $checkout = Http::withBasicAuth(env('PAYMONGO_SECRET_KEY'), '')
            ->post('https://api.paymongo.com/v1/checkout_sessions', [
                'data' => [
                    'attributes' => [
                        'line_items' => $lineItems,
                        'payment_method_types' => ['card', 'paymaya', 'qrph', 'billease', 'grab_pay', 'dob'],
                        'success_url' => route('recharge.success', ['userId' => $user->id]),
                        'cancel_url' => route('recharge.cancel'),
                    ],
                ],
            ]);

        // Check if the checkout creation was successful
        if ($checkout->successful()) {
            $checkoutUrl = $checkout['data']['attributes']['checkout_url'] ?? null;
            $transaction->update(['checkout_session_id' => $checkout['data']['id']]);

            return response()->json(['checkout_url' => $checkoutUrl]);
        } else {
            return response()->json(['error' => 'Failed to create recharge session'], 500);
        }
    }

    public function rechargeSuccess($userId, Request $request)
    {
        $user = User::findOrFail($userId);

        // Retrieve the most recent pending transaction
        $transaction = WalletTransaction::where('user_id', $userId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$transaction) {
            return redirect()->route('auction')->with('error', 'No pending transaction found.');
        }

        if ($transaction->status === 'completed') {
            return redirect()->route('auction')->with('status', 'Wallet already recharged successfully!');
        }

        // Proceed to update the wallet balance and mark transaction as completed
        $wholeAmount = $transaction->amount / 100;
        DB::table('user_wallets')->where('user_id', $user->id)->increment('balance', $wholeAmount);

        // Update the original transaction status to completed
        $transaction->update(['status' => 'completed']);

        return redirect()->route('auction')->with('status', 'Wallet recharged successfully!');
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        // Log the webhook payload for debugging
        // \Log::info('Webhook payload:', $payload);

        // Check if the transaction status is 'paid'
        if ($payload['data']['attributes']['status'] === 'paid') {
            $checkoutSessionId = $payload['data']['id'];
            $transaction = WalletTransaction::where('checkout_session_id', $checkoutSessionId)->first();

            if ($transaction) {
                if ($transaction->status === 'completed') {
                    // \Log::info('Transaction already completed for checkout session ID: ' . $checkoutSessionId);
                    return response()->json(['status' => 'success']);
                }

                // Update the user's wallet balance
                $wholeAmount = $transaction->amount / 100;
                $walletBalanceUpdate = DB::table('user_wallets')
                    ->where('user_id', $transaction->user_id)
                    ->increment('balance', $wholeAmount);

                // Update transaction status to completed
                $transaction->update(['status' => 'completed']);

                // Log successful wallet balance update
                if ($walletBalanceUpdate) {
                    // \Log::info('Wallet balance updated successfully for user ID: ' . $transaction->user_id);
                } else {
                    // \Log::warning('Failed to update wallet balance for user ID: ' . $transaction->user_id);
                }
            } else {
                // \Log::warning('Transaction not found for checkout session ID: ' . $checkoutSessionId);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function rechargeCancel()
    {
        return Inertia::render('Auction/Cancel', []);
    }
}
