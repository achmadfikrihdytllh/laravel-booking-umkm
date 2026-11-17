<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    private const TEXT = [
        'business_name' => 'required|string|max:80',
        'tagline' => 'nullable|string|max:160',
        'about' => 'nullable|string|max:3000',
        'whatsapp' => 'nullable|string|max:20',
        'wa_greeting' => 'nullable|string|max:200',
        'email' => 'nullable|email|max:120',
        'phone' => 'nullable|string|max:30',
        'address' => 'nullable|string|max:300',
        'maps_url' => 'nullable|url|max:500',
        'instagram' => 'nullable|string|max:60',
        'opening_hours' => 'nullable|string|max:300',
        'staff_label_id' => 'required|string|max:30',
        'staff_label_en' => 'required|string|max:30',
        'service_label_id' => 'required|string|max:30',
        'service_label_en' => 'required|string|max:30',
        'brand_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        'slot_step' => 'required|integer|in:10,15,20,30,45,60',
        'min_notice_hours' => 'required|integer|min:0|max:168',
        'max_advance_days' => 'required|integer|min:1|max:365',
    ];

    public function edit()
    {
        return view('admin.settings');
    }

    public function update(Request $request)
    {
        $rules = self::TEXT + ['logo' => 'nullable|image|max:2048', 'remove_logo' => 'nullable|boolean'];
        $d = $request->validate($rules);

        foreach (array_keys(self::TEXT) as $key) {
            Setting::write($key, $d[$key] ?? null);
        }
        Setting::write('auto_confirm', $request->boolean('auto_confirm') ? 1 : 0);

        $old = Setting::read('logo_path');
        if ($request->hasFile('logo')) {
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            Setting::write('logo_path', $request->file('logo')->store('brand', 'public'));
        } elseif ($request->boolean('remove_logo') && $old) {
            Storage::disk('public')->delete($old);
            Setting::write('logo_path', null);
        }

        return back()->with('ok', __('admin.saved'));
    }
}
