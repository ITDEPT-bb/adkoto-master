<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
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

        $items = Item::with('user', 'category', 'images')->where('is_sold', false)->get();
        return Inertia::render('Kalakalkoto/Home', [
            'items' => $items,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Items/Create', [
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
                $path = $image->store('items', 'public');
                $item->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('items.show', $item->id);
    }

    public function show($id)
    {
        $item = Item::with('user', 'category', 'images')->findOrFail($id);
        return Inertia::render('Kalakalkoto/Item', [
            'item' => $item
        ]);
    }

    public function markAsSold($id)
    {
        $item = Item::findOrFail($id);
        $this->authorize('update', $item);

        $item->is_sold = true;
        $item->save();

        return redirect()->route('items.index');
    }
}
