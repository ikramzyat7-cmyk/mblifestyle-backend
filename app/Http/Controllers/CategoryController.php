<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
{
    if ($request->has('admin')) {
        return Category::orderBy('order')->get();
    }
    return Category::where('is_visible', true)->orderBy('order')->get();
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'subcategories' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        if ($request->filled('subcategories')) {
            $validated['subcategories'] = json_decode($request->subcategories, true);
        }

        $category = Category::create($validated);
        \App\Models\ActivityLog::record(
            'category_created',
            "Catégorie ajoutée : {$category->name}",
            'Category',
            $category->id
        );

        return response()->json($category, 201);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'subcategories' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        if ($request->filled('subcategories')) {
            $validated['subcategories'] = json_decode($request->subcategories, true);
        }

        $category->update($validated);
        \App\Models\ActivityLog::record(
            'category_updated',
            "Catégorie modifiée : {$category->name}",
            'Category',
            $category->id
        );
        return response()->json($category, 200);
    }
    public function toggleVisibility(Request $request, Category $category)
{
    $validated = $request->validate([
        'is_visible' => 'required|boolean',
        'hidden_subcategories' => 'nullable|array',
    ]);

    $category->update($validated);
    return response()->json($category);
}

    public function destroy(Category $category)
    {
        $category->delete();
        \App\Models\ActivityLog::record(
            'category_deleted',
            "Catégorie supprimée : {$category->name}",
            'Category'
        );
        return response()->json(['message' => 'Catégorie supprimée'], 200);
    }
}