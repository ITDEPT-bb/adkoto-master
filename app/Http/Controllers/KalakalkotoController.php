<?php

namespace App\Http\Controllers;

use App\Models\KalakalkotoCategory;
use App\Models\KalakalkotoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class KalakalkotoController extends Controller
{
    public function index()
    {
        $kalakalitems = KalakalkotoItem::with(['attachments', 'user', 'category'])
            ->orderByDesc('created_at')
            ->paginate(12);

        $kalakalitems->each(function ($kalakal) {
            $kalakal->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        $categories = KalakalkotoCategory::all();

        return Inertia::render('Kalakalkoto/Index', [
            'kalakalitems' => $kalakalitems,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $categories = KalakalkotoCategory::all();
        return Inertia::render('Kalakalkoto/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:kalakalkoto_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Create the item
        $kalakalitem = KalakalkotoItem::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'location' => $request->location,
            'category_id' => $request->category_id,
        ]);

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('kalakalkoto_images', 'public');
                $kalakalitem->attachments()->create([
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('kalakalkoto')->with('success', 'Item created successfully.');
    }

    public function show($id)
    {
        $kalakalitem = KalakalkotoItem::with(['attachments', 'user', 'category'])
            ->findOrFail($id);

        $kalakalitem->attachments->each(function ($attachment) {
            $attachment->image_path = asset('storage/' . $attachment->image_path);
        });

        return Inertia::render('Kalakalkoto/Show', [
            'kalakalitem' => $kalakalitem,
        ]);
    }

    public function showUserItems()
    {
        $userId = Auth::id();

        $kalakalitems = KalakalkotoItem::where('user_id', $userId)
            ->with(['attachments', 'user', 'category'])
            ->orderByDesc('created_at')
            ->paginate(12);

        $kalakalitems->each(function ($kalakal) {
            $kalakal->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        $categories = KalakalkotoCategory::all();

        return Inertia::render('Kalakalkoto/Manage', [
            'kalakalitems' => $kalakalitems,
            'categories' => $categories,
            'authId' => $userId
        ]);
    }

    public function edit($id)
    {
        $kalakalitem = KalakalkotoItem::findOrFail($id);
        $categories = KalakalkotoCategory::all();

        return Inertia::render('Kalakalkoto/Edit', [
            'kalakalitem' => $kalakalitem,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:kalakalkoto_categories,id',
            'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $kalakalitem = KalakalkotoItem::findOrFail($id);

        $kalakalitem->update([
            'title' => $request->input('title'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'location' => $request->input('location'),
            'category_id' => $request->input('category_id'),
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $path = $attachment->store('public/attachments');
                $kalakalitem->attachments()->create([
                    'image_path' => str_replace('public/', '', $path),
                ]);
            }
        }

        return redirect()->route('kalakalkoto.index')->with('success', 'Item updated successfully.');
    }

    public function destroy($id)
    {
        $kalakalitem = KalakalkotoItem::findOrFail($id);
        if ($kalakalitem->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $kalakalitem->delete();

        return response()->json(['message' => 'Listing deleted successfully.']);
    }

    public function showCategory($category_name)
    {

        $category = KalakalkotoCategory::where('name', $category_name)->firstOrFail();

        $kalakalitems = KalakalkotoItem::with(['attachments', 'user', 'category'])
            ->orderByDesc('created_at')
            ->where('category_id', $category->id)
            ->paginate(12);

        $kalakalitems->each(function ($kalakal) {
            $kalakal->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        $categories = KalakalkotoCategory::all();

        return Inertia::render('Kalakalkoto/CategoryPage', [
            'kalakalitems' => $kalakalitems,
            'category' => $category,
            'categories' => $categories
        ]);
    }
}
