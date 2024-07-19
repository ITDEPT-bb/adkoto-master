<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return Inertia::render('Categories/Index', [
            'categories' => $categories
        ]);
    }

    public function show($id)
    {
        $category = Category::with('items')->findOrFail($id);
        return Inertia::render('Categories/Show', [
            'category' => $category
        ]);
    }
}
