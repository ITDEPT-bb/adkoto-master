<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutPageController extends Controller
{
    public function index(Request $request)
    {
        $featuredAds = Advertisement::with(['attachments', 'user', 'category'])
            ->where('featured', true)
            ->orderByDesc('created_at')
            ->get();

        $featuredAds->each(function ($ad) {
            $ad->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        return Inertia::render('About', [
            'featuredAds' => $featuredAds,
        ]);
    }
}
