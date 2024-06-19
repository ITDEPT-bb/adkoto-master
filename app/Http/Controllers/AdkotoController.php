<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\AdsAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class AdkotoController extends Controller
{

    /**
     * Show the ad listing or dashboard.
     *
     * @return Response
     */
    public function index(): Response
    {
        $ads = Ad::where('user_id', Auth::id())->with('attachments')->orderByDesc('created_at')->get();

        return Inertia::render('Adkoto/Home', [
            'ads' => $ads,
        ]);
    }

    /**
     * Show the create ad form.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Adkoto/Create');
    }

    public function fetchCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
    // public function create(): Response
    // {
    //     $categories = Category::all();

    //     return Inertia::render('Adkoto/Create', [
    //         'categories' => $categories,
    //     ]);
    // }


    /**
     * Store a newly created ad in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048'
        ]);

        // Create the ad
        $ad = Ad::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'user_id' => Auth::id()
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ads', 'public');

            // Create ads_attachment record
            AdsAttachment::create([
                'ad_id' => $ad->id,
                'image_path' => $imagePath,
            ]);
        }

        return Redirect::route('adkoto')->with('success', 'Ad created successfully.');
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
