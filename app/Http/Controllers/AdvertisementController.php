<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of advertisement categories with subcategories.
     */
    public function index()
    {
        $advertisements = Advertisement::with('attachments')
            ->orderByDesc('created_at')
            ->get();

        $categories = AdvertisementCategory::with('subCategories')->get();

        return Inertia::render('Advertisements/Index', [
            'advertisements' => $advertisements,
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new advertisement.
     */
    public function create()
    {
        $categories = AdvertisementCategory::with('subCategories')->get();

        return Inertia::render('Advertisements/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created advertisement in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:advertisement_categories,id',
            'sub_category_id' => 'required|exists:advertisement_sub_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('advertisements', 'public');
                $imagePaths[] = $path;
            }
        }

        Advertisement::create([
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'location' => $request->location,
            'images' => $imagePaths,
        ]);

        return redirect()->route('advertisements.index')->with('success', 'Advertisement created successfully.');
    }
}

