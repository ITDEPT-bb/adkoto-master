<?php

namespace App\Http\Controllers;

use App\Models\KalakalkotoCategory;
use App\Models\KalakalkotoItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KalakalkotoController extends Controller
{
    public function index()
    {
        $kalakalitems = KalakalkotoItem::with(['attachments', 'user', 'category'])
            ->orderByDesc('created_at')
            // ->where('featured', false)
            ->paginate(12);

        $kalakalitems->each(function ($kalakal) {
            $kalakal->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        // $featuredItems = KalakalkotoItem::with(['attachments', 'user', 'category'])
        //     ->where('featured', true)
        //     ->orderByDesc('created_at')
        //     ->limit(2)
        //     ->get();

        // $featuredItems->each(function ($kalakal) {
        //     $kalakal->attachments->each(function ($attachment) {
        //         $attachment->image_path = asset('storage/' . $attachment->image_path);
        //     });
        // });

        $categories = KalakalkotoCategory::all();

        return Inertia::render('Kalakalkoto/Index', [
            'kalakalitems' => $kalakalitems,
            // 'featuredItems' => $featuredItems,
            'categories' => $categories
        ]);
    }
}
