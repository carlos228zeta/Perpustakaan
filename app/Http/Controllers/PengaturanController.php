<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LibrarySetting;

class PengaturanController extends Controller
{
    public function index()
    {
        $settings = DB::table('library_settings')->pluck('value', 'key')->all();
        return view('admin.pengaturan.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'max_student_borrow' => 'required|integer|min:1|max:10',
            'max_teacher_borrow' => 'required|integer|min:1|max:20',
            'student_borrow_days' => 'required|integer|min:1|max:30',
            'teacher_borrow_days' => 'required|integer|min:1|max:60',
            'fine_per_day' => 'required|numeric|min:0',
            'institution_name' => 'required|string|max:255',
            'app_title' => 'nullable|string|max:255',
            'app_subtitle' => 'nullable|string|max:255',
            'theme_primary_color' => 'nullable|string|max:20',
            'institution_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'auth_hero_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'librarian_signature_img' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'institution_stamp_img' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'auth_login_title' => 'nullable|string|max:255',
            'auth_login_subtitle' => 'nullable|string|max:255',
            'auth_hero_title' => 'nullable|string|max:255',
            'auth_hero_subtitle' => 'nullable|string|max:500',
            'head_librarian_name' => 'nullable|string|max:255',
            'head_librarian_nip' => 'nullable|string|max:100',
            'kop_foundation_name' => 'nullable|string|max:255',
            'kop_units_text' => 'nullable|string|max:255',
            'kop_letter_no' => 'nullable|string|max:100',
            'kop_city_name' => 'nullable|string|max:100',
            'footer_description' => 'nullable|string|max:500',
            'library_address' => 'nullable|string|max:255',
            'library_phone' => 'nullable|string|max:50',
            'library_email' => 'nullable|string|max:100',
            'operating_hours_weekday' => 'nullable|string|max:100',
            'operating_hours_weekend' => 'nullable|string|max:100',
            'layanan_tata_tertib' => 'nullable|string|max:2000',
            'layanan_ruang_baca' => 'nullable|string|max:2000',
            'layanan_wifi' => 'nullable|string|max:2000',
            'layanan_faq' => 'nullable|string|max:2000',
        ]);

        if ($request->hasFile('institution_logo')) {
            $file = $request->file('institution_logo');
            $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/logo'), $fileName);
            LibrarySetting::set('institution_logo', 'images/logo/' . $fileName);
        }

        if ($request->hasFile('auth_hero_image')) {
            $file = $request->file('auth_hero_image');
            $fileName = 'hero_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/hero'), $fileName);
            LibrarySetting::set('auth_hero_image', 'images/hero/' . $fileName);
        }

        if ($request->hasFile('librarian_signature_img')) {
            $file = $request->file('librarian_signature_img');
            $fileName = 'sig_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/signatures'), $fileName);
            LibrarySetting::set('librarian_signature_img', 'images/signatures/' . $fileName);
        }

        if ($request->hasFile('institution_stamp_img')) {
            $file = $request->file('institution_stamp_img');
            $fileName = 'stamp_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/signatures'), $fileName);
            LibrarySetting::set('institution_stamp_img', 'images/signatures/' . $fileName);
        }

        foreach ($request->only([
            'max_student_borrow',
            'max_teacher_borrow',
            'student_borrow_days',
            'teacher_borrow_days',
            'fine_per_day',
            'institution_name',
            'app_title',
            'app_subtitle',
            'theme_primary_color',
            'auth_login_title',
            'auth_login_subtitle',
            'auth_hero_title',
            'auth_hero_subtitle',
            'head_librarian_name',
            'head_librarian_nip',
            'kop_foundation_name',
            'kop_units_text',
            'kop_letter_no',
            'kop_city_name',
            'footer_description',
            'library_address',
            'library_phone',
            'library_email',
            'operating_hours_weekday',
            'operating_hours_weekend',
            'layanan_tata_tertib',
            'layanan_ruang_baca',
            'layanan_wifi',
            'layanan_faq',
        ]) as $key => $value) {
            if ($value !== null) {
                LibrarySetting::set($key, $value);
            }
        }

        $activeTab = $request->input('active_tab', 'sirkulasi');
        return redirect()->route('pengaturan.index', ['tab' => $activeTab])->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
