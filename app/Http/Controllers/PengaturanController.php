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
        ]);

        foreach ($request->only([
            'max_student_borrow',
            'max_teacher_borrow',
            'student_borrow_days',
            'teacher_borrow_days',
            'fine_per_day',
            'institution_name'
        ]) as $key => $value) {
            LibrarySetting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Pengaturan perpustakaan berhasil diperbarui!');
    }
}
