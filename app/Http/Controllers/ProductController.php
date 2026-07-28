<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::orderBy('display_order')->orderBy('created_at', 'desc')->get();
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:products,id',
            'orders.*.display_order' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $item) {
            Product::where('id', $item['id'])->update(['display_order' => $item['display_order']]);
        }

        return response()->json(['message' => 'Ordre mis à jour']);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->canManageStock()) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'subcategory' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'images.*' => 'nullable|file|max:10240',
            'colors' => 'nullable|string',
            'sizes' => 'nullable|string',
            'stock' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
        ]);

        if ($request->hasFile('images')) {
            $files = $request->file('images');
            ksort($files);
            $paths = [];
            foreach ($files as $file) {
                $paths[] = $file->store('products', 'public');
            }
            $validated['images'] = $paths;
        }

        if ($request->filled('colors')) {
    $colorsData = json_decode($request->colors, true);
    foreach ($colorsData as &$color) {
        $hexKey = strtolower(str_replace('#', '', $color['hex']));
        $imageKey = "color_images_{$hexKey}";
        $files = $request->file($imageKey);
        if ($files && is_array($files)) {
            $paths = [];
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $paths[] = $file->store('products', 'public');
                }
            }
            if (!empty($paths)) {
                $color['images'] = $paths;
            }
        }
    }
    $validated['colors'] = $colorsData;
}

        if ($request->filled('sizes')) {
            $validated['sizes'] = json_decode($request->sizes, true);
        }

        $product = Product::create($validated);

        \App\Models\ActivityLog::record(
            'product_created',
            "Produit ajouté : {$product->name}",
            'Product',
            $product->id
        );

        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        if (!auth()->user()->canManageStock()) {
            return response()->json(['message' => 'Accès refusé — Seul un Super Admin ou Gestionnaire de stock peut modifier les produits.'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'subcategory' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'images.*' => 'nullable|file|max:10240',
            'colors' => 'nullable|string',
            'sizes' => 'nullable|string',
            'stock' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
        ]);

        if ($request->hasFile('images')) {
            $files = $request->file('images');
            ksort($files);
            $paths = [];
            foreach ($files as $file) {
                $paths[] = $file->store('products', 'public');
            }
            $validated['images'] = $paths;
        } elseif ($request->filled('existing_images_order')) {
            $validated['images'] = json_decode($request->existing_images_order, true);
        }

        if ($request->filled('colors')) {
    $colorsData = json_decode($request->colors, true);
    foreach ($colorsData as &$color) {
        $hexKey = strtolower(str_replace('#', '', $color['hex']));
        $imageKey = "color_images_{$hexKey}";
        $files = $request->file($imageKey);
        if ($files && is_array($files)) {
            $paths = [];
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $paths[] = $file->store('products', 'public');
                }
            }
            if (!empty($paths)) {
                $color['images'] = $paths;
            }
        }
    }
    $validated['colors'] = $colorsData;
}

        if ($request->filled('sizes')) {
            $validated['sizes'] = json_decode($request->sizes, true);
        }

        $oldStock = $product->stock;
        $product->update($validated);

        $changes = [];
        if ($oldStock != $product->fresh()->stock) {
            $changes['stock'] = ['avant' => $oldStock, 'après' => $product->fresh()->stock];
        }

        \App\Models\ActivityLog::record(
            'product_updated',
            "Produit modifié : {$product->name}",
            'Product',
            $product->id,
            $changes ?: null
        );

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        if (!auth()->user()->canManageStock()) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $name = $product->name;
        $product->delete();

        \App\Models\ActivityLog::record(
            'product_deleted',
            "Produit supprimé : {$name}",
            'Product'
        );

        return response()->json(null, 204);
    }
}