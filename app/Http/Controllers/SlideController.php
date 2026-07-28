<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function index()
    {
        return Slide::where('is_active', true)->orderBy('order')->get();
    }

    public function adminIndex()
    {
        return Slide::orderBy('order')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|string',
            'image' => 'nullable|file|max:5120',
            'video' => 'nullable|file|max:102400',
            'type' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);
        \Log::info('Files reçus:', ['files' => array_keys($request->allFiles()), 'has_video' => $request->hasFile('video')]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('slides', 'public');
            $validated['type'] = 'image';
        }

        if ($request->hasFile('video')) {
            $validated['video'] = $request->file('video')->store('slides', 'public');
            $validated['type'] = 'video';
        }

        if (!isset($validated['type'])) {
            $validated['type'] = 'image';
        }
        if ($request->hasFile('product_image')) {
    $validated['product_image'] = $request->file('product_image')->store('slides', 'public');
}
if ($request->has('style')) $validated['style'] = $request->style;
if ($request->has('promo_amount')) $validated['promo_amount'] = $request->promo_amount;
if ($request->has('promo_sub')) $validated['promo_sub'] = $request->promo_sub;

        $slide = Slide::create($validated);
        return response()->json($slide, 201);
    }

    public function update(Request $request, Slide $slide)
    {
        $data = [];

        if ($request->has('title')) $data['title'] = $request->title;
        if ($request->has('subtitle')) $data['subtitle'] = $request->subtitle;
        if ($request->has('button_text')) $data['button_text'] = $request->button_text;
        if ($request->has('button_link')) $data['button_link'] = $request->button_link;
        if ($request->has('order')) $data['order'] = intval($request->order);
        if ($request->has('is_active')) $data['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
        if ($request->has('type')) $data['type'] = $request->type;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('slides', 'public');
            $data['type'] = 'image';
            $data['video'] = null;
        }

        if ($request->hasFile('video')) {
            $data['video'] = $request->file('video')->store('slides', 'public');
            $data['type'] = 'video';
            $data['image'] = null;
        }
        if ($request->hasFile('product_image')) {
    $data['product_image'] = $request->file('product_image')->store('slides', 'public');
}
if ($request->has('style')) $data['style'] = $request->style;
if ($request->has('promo_amount')) $data['promo_amount'] = $request->promo_amount;
if ($request->has('promo_sub')) $data['promo_sub'] = $request->promo_sub;

        $slide->update($data);
        return response()->json($slide);
    }

    public function destroy(Slide $slide)
    {
        $slide->delete();
        return response()->json(['message' => 'Slide supprimé']);
    }
}