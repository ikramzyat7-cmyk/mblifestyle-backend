<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return Review::where('status', 'approved')
            ->latest()
            ->get();
    }

    public function adminIndex()
    {
        return Review::latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'product' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $data = $request->validate([
    'name' => 'required|string|max:255',
    'rating' => 'required|integer|min:1|max:5',
    'comment' => 'required|string',
    'product' => 'nullable|string',
    'image' => 'nullable|file|max:5120',
]);

if ($request->hasFile('image')) {
    $data['image'] = $request->file('image')->store('reviews', 'public');
}

$review = Review::create($data);
        return response()->json($review, 201);
    }

    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);
        return response()->json($review);
    }

    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);
        return response()->json($review);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->json(['message' => 'Avis supprimé']);
    }
}