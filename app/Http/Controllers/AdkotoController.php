<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Advertisement;
use App\Models\AdvertisementCategory;
use App\Models\Category;
use App\Models\AdsAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class AdkotoController extends Controller
{

    public function index()
    {
        $advertisements = Advertisement::with('attachments')
            ->orderByDesc('created_at')
            ->get();

        $categories = AdvertisementCategory::with('subCategories')->get();

        return Inertia::render('Adkoto/Index', [
            'advertisements' => $advertisements,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $categories = AdvertisementCategory::with('subCategories')->get();

        return Inertia::render('Adkoto/Create', [
            'categories' => $categories
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:advertisement_categories,id',
            'sub_category_id' => 'required|exists:advertisement_sub_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'images.*' => 'sometimes|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Create the advertisement
        $advertisement = Advertisement::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'location' => $request->location,
        ]);

        // Handle file uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('advertisement_images', 'public');
                $advertisement->attachments()->create(['image_path' => $path]);
            }
        }

        // Redirect or return response
        return redirect()->route('advertisements.create')->with('success', 'Advertisement created successfully!');
    }
}
