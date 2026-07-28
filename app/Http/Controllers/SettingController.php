<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->key] = $setting->value;
        }
        return response()->json($result);
    }

    public function update(Request $request)
    {
        $fields = [
            'whatsapp_number', 'email', 'shop_name',
            'instagram_url', 'address', 'working_hours',
            'callmebot_api_key', 'promo_banner',
            'promo_title', 'promo_text', 'promo_btn', 'promo_link',
            'lookbook_title_1', 'lookbook_link_1',
            'lookbook_title_2', 'lookbook_link_2',
            'lookbook_title_3', 'lookbook_link_3',
        ];
    
        foreach ($fields as $key) {
            if ($request->has($key)) {
                \App\Models\Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $request->input($key)]
                );
            }
        }
    
        if ($request->hasFile('nouveautes_image_file')) {
            $path = $request->file('nouveautes_image_file')->store('settings', 'public');
            \App\Models\Setting::updateOrCreate(
                ['key' => 'nouveautes_image'],
                ['value' => $path]
            );
        }
    
        if ($request->hasFile('promo_image_file')) {
            $path = $request->file('promo_image_file')->store('settings', 'public');
            \App\Models\Setting::updateOrCreate(
                ['key' => 'promo_image'],
                ['value' => $path]
            );
        }
    
        foreach ([1, 2, 3] as $n) {
            if ($request->hasFile("lookbook_image_{$n}_file")) {
                $path = $request->file("lookbook_image_{$n}_file")->store('settings', 'public');
                \App\Models\Setting::updateOrCreate(
                    ['key' => "lookbook_image_{$n}"],
                    ['value' => $path]
                );
            }
        }
    
        return response()->json(['message' => 'Paramètres mis à jour']);
    }
}