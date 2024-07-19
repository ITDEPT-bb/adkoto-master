<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = Transaction::with('item')->where('buyer_id', $user->id)->get();
        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions
        ]);
    }

    public function show($id)
    {
        $transaction = Transaction::with('item', 'buyer')->findOrFail($id);
        return Inertia::render('Transactions/Show', [
            'transaction' => $transaction
        ]);
    }
}
