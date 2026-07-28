<?php
namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        return Banner::where('is_active', true)->get()->keyBy('position');
    }

    public function adminIndex()
    {
        return Banner::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'badge_text' => 'nullable|string',
            'link' => 'nullable|string',
            'image' => 'nullable|file|max:10240',
            'position' => 'required|in:main,top-right,bottom-right',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner = Banner::create($data);
        return response()->json($banner, 201);
    }

    public function update(Request $request, Banner $banner)
    {
        $data = [];
        if ($request->has('title')) $data['title'] = $request->title;
        if ($request->has('subtitle')) $data['subtitle'] = $request->subtitle;
        if ($request->has('badge_text')) $data['badge_text'] = $request->badge_text;
        if ($request->has('link')) $data['link'] = $request->link;
        if ($request->has('position')) $data['position'] = $request->position;
        if ($request->has('is_active')) $data['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);
        return response()->json($banner);
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return response()->json(['message' => 'Bannière supprimée']);
    }
}