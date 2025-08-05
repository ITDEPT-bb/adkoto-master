<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class LiveAuctionController extends Controller
{
    public function index()
    {
        $appID = env('AGORA_APP_ID');
        if (!$appID) {
            return response()->json(['error' => 'Agora App ID is not configured'], 500);
        }

        return Inertia::render('Auction/LiveStream', [
            'appId' => $appID,
        ]);
    }
}
