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

    /**
     * Display the specified ad.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $ad = Ad::with('attachments')->findOrFail($id);

        return Inertia::render('Adkoto/Show', [
            'ad' => $ad,
        ]);
    }

    /**
     * Show the form for editing the specified ad.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(int $id): Response
    {
        $ad = Ad::findOrFail($id);
        $categories = Category::all();

        return Inertia::render('Adkoto/Edit', [
            'ad' => $ad,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified ad in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $ad = Ad::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048'
        ]);

        // Update ad details
        $ad->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'location' => $request->location,
        ]);

        // Handle image update if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ads', 'public');

            // Update or create ads_attachment record
            $ad->attachments()->updateOrCreate([], [
                'ad_id' => $ad->id,
                'file_path' => $imagePath,
            ]);
        }

        return Redirect::route('adkoto.show', ['id' => $id]);
    }

    /**
     * Remove the specified ad from storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy(int $id)
    {
        $ad = Ad::findOrFail($id);
        $ad->delete();

        return Redirect::route('adkoto');
    }
}
