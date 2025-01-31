<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Advertisement;
use App\Models\AdvertisementAttachment;
use App\Models\AdvertisementCategory;
use App\Models\AdvertisementSubCategory;
use App\Models\Category;
use App\Models\AdsAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AdkotoController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::with(['attachments', 'user', 'category'])
            ->orderByDesc('created_at')
            ->paginate(5);

        $advertisements->each(function ($ad) {
            $ad->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        $featuredAds = Advertisement::with(['attachments', 'user', 'category'])
            ->where('featured', true)
            ->orderByDesc('created_at')
            ->get();

        $featuredAds->each(function ($ad) {
            $ad->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        $sponsoredAds = Advertisement::with(['attachments', 'user', 'category'])
            ->where('sponsored', true)
            ->orderByDesc('created_at')
            ->get();

        $sponsoredAds->each(function ($ad) {
            $ad->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        $categories = AdvertisementCategory::with([
            'subCategories' => function ($query) {
                $query->withCount('advertisements');
            }
        ])
            ->withCount('advertisements')
            ->get();

        return Inertia::render('Adkoto/Index', [
            'advertisements' => $advertisements,
            'featuredAds' => $featuredAds,
            'sponsoredAds' => $sponsoredAds,
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

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('advertisement_images', 'public');
                $advertisement->attachments()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('adkoto')->with('success', 'Advertisement created successfully!');
    }

    public function showCategory($category_name)
    {
        $category = AdvertisementCategory::withCount('advertisements')
            ->where('name', $category_name)
            ->with('subCategories.advertisements')
            ->firstOrFail();

        $advertisements = Advertisement::where('category_id', $category->id)
            ->with(['attachments', 'user', 'category'])
            ->paginate(5);

        $advertisements->each(function ($ad) {
            $ad->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        $categories = AdvertisementCategory::with([
            'subCategories' => function ($query) {
                $query->withCount('advertisements');
            }
        ])
            ->withCount('advertisements')
            ->get();

        return Inertia::render('Adkoto/CategoryPage', [
            'category' => $category,
            'categories' => $categories,
            'advertisements' => $advertisements,
        ]);
    }

    public function showSubCategory($category_name, $subcategory_name)
    {
        $category = AdvertisementCategory::where('name', $category_name)
            ->with('subCategories.advertisements')
            ->firstOrFail();

        $subCategory = AdvertisementSubCategory::where('name', $subcategory_name)
            ->where('category_id', $category->id)
            ->withCount('advertisements')
            ->firstOrFail();

        $advertisements = Advertisement::where('sub_category_id', $subCategory->id)
            ->with(['attachments', 'user', 'category'])
            ->paginate(5);

        $advertisements->each(function ($ad) {
            $ad->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        $categories = AdvertisementCategory::with([
            'subCategories' => function ($query) {
                $query->withCount('advertisements');
            }
        ])
            ->withCount('advertisements')
            ->get();

        return Inertia::render('Adkoto/SubCategoryPage', [
            'subCategory' => $subCategory,
            'categories' => $categories,
            'advertisements' => $advertisements,
        ]);
    }

    public function show($id)
    {
        $advertisement = Advertisement::with(['attachments', 'user', 'category'])
            ->findOrFail($id);

        $advertisement->attachments->each(function ($attachment) {
            $attachment->image_path = asset('storage/' . $attachment->image_path);
        });

        return Inertia::render('Adkoto/Show', [
            'advertisement' => $advertisement,
        ]);
    }

    public function showUserAds()
    {
        $userId = Auth::id();

        $advertisements = Advertisement::where('user_id', $userId)
            ->with(['attachments', 'user', 'category'])
            ->paginate(5);

        $advertisements->each(function ($ad) {
            $ad->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        return Inertia::render('Adkoto/Manage', [
            'advertisements' => $advertisements,
        ]);
    }

    public function edit($id)
    {
        $advertisement = Advertisement::with(['attachments', 'user', 'category'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $categories = AdvertisementCategory::with('subCategories')->get();

        return Inertia::render('Adkoto/Edit', [
            'advertisement' => $advertisement,
            'categories' => $categories,
            'existingImages' => $advertisement->attachments->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'url' => asset('storage/' . $attachment->image_path),
                ];
            }),
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'category_id' => 'required|exists:advertisement_categories,id',
            'sub_category_id' => 'required|exists:advertisement_sub_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'images.*' => 'sometimes|file|mimes:jpg,jpeg,png|max:2048',
            'remove_images.*' => 'exists:advertisement_attachments,id',
        ]);

        // Find the advertisement
        $advertisement = Advertisement::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Update the advertisement details
        $advertisement->update([
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'location' => $request->location,
        ]);

        // Remove images if any
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageId) {
                $attachment = AdvertisementAttachment::findOrFail($imageId);
                Storage::disk('public')->delete($attachment->image_path);
                $attachment->delete();
            }
        }

        // Add new images if any
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('advertisement_images', 'public');
                $advertisement->attachments()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('adkoto')->with('success', 'Advertisement updated successfully!');
    }

    public function destroy($id)
    {
        $advertisement = Advertisement::findOrFail($id);

        if ($advertisement->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $advertisement->delete();

        return response()->json(['message' => 'Advertisement deleted successfully.']);
    }
}
