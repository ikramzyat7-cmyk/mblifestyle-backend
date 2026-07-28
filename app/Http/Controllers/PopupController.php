<?php

namespace App\Http\Controllers;

use App\Models\PopupSetting;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    public function index()
{
    $popup = PopupSetting::first();
    if (!$popup) return response()->json(null);

    $productIds = $popup->product_ids ?? [];
    $products = \App\Models\Product::whereIn('id', $productIds)->get();

    return response()->json([
        'is_active' => $popup->is_active,
        'title' => $popup->title,
        'subtitle' => $popup->subtitle,
        'products' => $products,
    ]);
}

public function update(Request $request)
{
    $validated = $request->validate([
        'is_active' => 'boolean',
        'product_ids' => 'nullable|array',
        'product_ids.*' => 'exists:products,id',
        'title' => 'nullable|string|max:255',
        'subtitle' => 'nullable|string|max:255',
    ]);

    $popup = PopupSetting::first();
    if (!$popup) {
        $popup = PopupSetting::create($validated);
    } else {
        $popup->update($validated);
    }

    return response()->json($popup);
}
}