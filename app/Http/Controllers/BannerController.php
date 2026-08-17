<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LibrarySetting;

class BannerController extends Controller
{
    public function index()
    {
        $settings = DB::table('library_settings')->pluck('value', 'key')->all();
        $heroSlides = isset($settings['hero_slides_list']) ? json_decode($settings['hero_slides_list'], true) : [];
        if (!is_array($heroSlides)) {
            $heroSlides = [];
        }
        return view('admin.banner.index', compact('settings', 'heroSlides'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'banner_badge' => 'nullable|string|max:100',
            'banner_title' => 'nullable|string|max:255',
            'banner_subtitle' => 'nullable|string|max:500',
            'banner_button_text' => 'nullable|string|max:100',
            'banner_button_link' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'hero_slides.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'footer_description' => 'nullable|string|max:500',
            'library_address' => 'nullable|string|max:255',
            'library_phone' => 'nullable|string|max:50',
            'library_email' => 'nullable|string|max:100',
            'operating_hours_weekday' => 'nullable|string|max:100',
            'operating_hours_weekend' => 'nullable|string|max:100',
        ]);

        $settingsToSave = $request->only([
            'banner_badge',
            'banner_title',
            'banner_subtitle',
            'banner_button_text',
            'banner_button_link',
            'footer_description',
            'library_address',
            'library_phone',
            'library_email',
            'operating_hours_weekday',
            'operating_hours_weekend',
        ]);

        // Handle single featured banner image
        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = 'banner_' . time() . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/banners');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $settingsToSave['banner_image'] = 'uploads/banners/' . $filename;
        }

        // Handle multi-upload for Hero Slider background photos
        if ($request->hasFile('hero_slides')) {
            $existingJson = LibrarySetting::get('hero_slides_list');
            $currentSlides = $existingJson ? json_decode($existingJson, true) : [];
            if (!is_array($currentSlides)) {
                $currentSlides = [];
            }

            $destPath = public_path('uploads/hero_slides');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }

            foreach ($request->file('hero_slides') as $idx => $file) {
                $filename = 'hero_slide_' . time() . '_' . $idx . '.' . $file->getClientOriginalExtension();
                $file->move($destPath, $filename);
                $currentSlides[] = 'uploads/hero_slides/' . $filename;
            }

            $settingsToSave['hero_slides_list'] = json_encode(array_values($currentSlides));
        }

        foreach ($settingsToSave as $key => $value) {
            if ($value !== null) {
                LibrarySetting::set($key, $value);
            }
        }

        return redirect()->back()->with('success', 'Banner dan foto Hero Slider berhasil diperbarui!');
    }

    public function deleteSlide($index)
    {
        $existingJson = LibrarySetting::get('hero_slides_list');
        $currentSlides = $existingJson ? json_decode($existingJson, true) : [];

        if (is_array($currentSlides) && isset($currentSlides[$index])) {
            $filePath = public_path($currentSlides[$index]);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            unset($currentSlides[$index]);
            LibrarySetting::set('hero_slides_list', json_encode(array_values($currentSlides)));
        }

        return redirect()->back()->with('success', 'Foto Hero Slider berhasil dihapus!');
    }
}
