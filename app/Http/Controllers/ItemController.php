<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\ItemAttachment;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

class ItemController extends Controller
{

    use AuthorizesRequests;

    public function index()
    {
        $categories = Category::all();
        $currentUserId = Auth::id();

        // $items = Item::with('user', 'category', 'images')->where('is_sold', false)->get();
        $items = Item::with('user', 'category', 'images')
            ->where('is_sold', false)
            ->where('user_id', '!=', $currentUserId)
            ->get();

        return Inertia::render('Kalakalkoto/Home', [
            'items' => $items,
            'categories' => $categories
        ]);
    }

    public function userItems()
    {
        $categories = Category::all();
        $currentUserId = Auth::id();

        // $items = Item::with('user', 'category', 'images')->where('is_sold', false)->get();
        $items = Item::with('user', 'category', 'images')
            ->where('is_sold', false)
            ->where('user_id', '=', $currentUserId)
            ->get();

        return Inertia::render('Kalakalkoto/Listing', [
            'items' => $items,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Kalakalkoto/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $item = new Item($request->all());
        $item->user_id = Auth::id();
        $item->save();

        // Handle file uploads if any
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // $path = $image->store('items', 'public');
                $path = $image->store('items/' . $item->id, 'public');
                $item->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('kalakalkoto.item.show', $item->id);
    }

    public function show($id)
    {
        $item = Item::with('user', 'category', 'images')->findOrFail($id);
        return Inertia::render('Kalakalkoto/Item', [
            'item' => $item
        ]);
    }

    public function showItem($id)
    {
        $item = Item::with('user', 'category', 'images')->findOrFail($id);
        return Inertia::render('Kalakalkoto/ItemDetail', [
            'item' => $item,
        ]);
    }

    public function markAsSold($id)
    {
        $item = Item::findOrFail($id);
        // $this->authorize('update', $item);

        $item->is_sold = true;
        $item->save();

        return response()->json(['redirect' => route('kalakalkoto.userItems')]);
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        return response()->json(['redirect' => route('kalakalkoto.userItems')]);
    }

    public function filterByCategory($categoryId)
    {
        $categories = Category::all();
        $items = Item::with('user', 'category', 'images')
            ->where('category_id', $categoryId)
            ->where('is_sold', false)
            ->get();

        return Inertia::render('Kalakalkoto/Home', [
            'categories' => $categories,
            'items' => $items,
            'currentCategoryId' => $categoryId
        ]);
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all();
        return Inertia::render('Kalakalkoto/Edit', [
            'item' => $item,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Update the item details
        $item->update($request->only(['title', 'description', 'price', 'location', 'category_id']));

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items/' . $item->id, 'public');
                ItemAttachment::create(['item_id' => $item->id, 'path' => $path]);
            }
        }

        // Optionally, handle removing old images if required
        // $item->images()->whereNotIn('path', $request->input('existing_images', []))->delete();

        return response()->json(['redirect' => route('kalakalkoto.showItem', $id)]);
    }

}
