<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Advertisement;
use App\Models\AdvertisementCategory;
use App\Models\AdvertisementSubCategory;
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
        $advertisements = Advertisement::with(['attachments', 'user', 'category'])
            ->orderByDesc('created_at')
            ->get();

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
            ->get();

        $advertisements->each(function ($ad) {
            $ad->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        return Inertia::render('Adkoto/CategoryPage', [
            'category' => $category,
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
            ->get();

        $advertisements->each(function ($ad) {
            $ad->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        return Inertia::render('Adkoto/SubCategoryPage', [
            'subCategory' => $subCategory,
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
            ->get();

        $advertisements->each(function ($ad) {
            $ad->attachments->each(function ($attachment) {
                $attachment->image_path = asset('storage/' . $attachment->image_path);
            });
        });

        return Inertia::render('Adkoto/Manage', [
            'advertisements' => $advertisements,
        ]);
    }
}
