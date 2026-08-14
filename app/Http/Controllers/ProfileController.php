<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = DB::table('students')->where('user_id', $user->id)->first();
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();

        return view('profile.index', compact('user', 'student', 'teacher'));
    }

    public function edit()
    {
        $user = auth()->user();
        $student = DB::table('students')->where('user_id', $user->id)->first();
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();

        return view('profile.edit', compact('user', 'student', 'teacher'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:20',
        ]);

        DB::table('users')->where('id', $user->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => now(),
        ]);

        if ($request->filled('password')) {
            DB::table('users')->where('id', $user->id)->update([
                'password' => Hash::make($request->password)
            ]);
        }

        if ($user->hasRole('student')) {
            DB::table('students')->where('user_id', $user->id)->update([
                'phone' => $request->phone,
                'updated_at' => now()
            ]);
        } elseif ($user->hasRole('teacher')) {
            DB::table('teachers')->where('user_id', $user->id)->update([
                'phone' => $request->phone,
                'updated_at' => now()
            ]);
        }

        return redirect()->route('profile.index')->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
