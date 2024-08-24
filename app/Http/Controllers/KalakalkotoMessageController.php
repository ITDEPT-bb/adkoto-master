<?php

namespace App\Http\Controllers;

use App\Models\KalakalkotoItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KalakalkotoMessageController extends Controller
{
    public function index()
    {
        $items = KalakalkotoItem::where('status', 'available')
            ->whereHas('conversations', function ($query) {
                $query->whereNotNull('id');
            })
            ->with(['conversations', 'conversations.buyer', 'conversations.seller'])
            ->get();

        return Inertia::render('Kalakalkoto/ItemConversation', [
            'items' => $items,
        ]);
    }
}
